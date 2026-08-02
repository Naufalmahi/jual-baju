<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class QrisCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
            'nisn_nip' => 'NISN' . Str::random(8),
            'username' => 'siswa_' . Str::random(6),
        ]);
    }

    private function makeOrder(User $user, float $amount = 100000, string $method = 'qris'): Order
    {
        return Order::create([
            'order_code' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'total_amount' => $amount,
            'payment_method' => $method,
            'status' => 'Menunggu Pembayaran',
        ]);
    }

    public function test_checkout_rejects_qris_when_qris_setting_disabled(): void
    {
        Setting::create(['key' => 'enable_qris', 'value' => '0']);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/siswa/checkout', [
            'payment_method' => 'qris',
        ]);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_checkout_accepts_qris_when_qris_setting_enabled(): void
    {
        Setting::create(['key' => 'enable_qris', 'value' => '1']);
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/siswa/checkout', [
            'payment_method' => 'qris',
        ]);

        $response->assertSessionDoesntHaveErrors('payment_method');
    }

    public function test_payQris_rejected_for_other_users_order(): void
    {
        $owner = $this->makeUser();
        $attacker = $this->makeUser();
        $order = $this->makeOrder($owner);

        $response = $this->actingAs($attacker)->postJson("/siswa/orders/{$order->id}/pay-qris");

        $response->assertStatus(403);
    }

    public function test_payQris_rejected_when_qris_setting_disabled(): void
    {
        Setting::create(['key' => 'enable_qris', 'value' => '0']);
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->actingAs($user)->postJson("/siswa/orders/{$order->id}/pay-qris");

        $response->assertStatus(403);
    }

    public function test_check_status_marks_order_ready_when_midtrans_settlement(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $this->mock(MidtransService::class, function ($mock) use ($order) {
            $mock->shouldReceive('getTransactionStatus')
                ->once()
                ->with($order->order_code)
                ->andReturn([
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept',
                    'transaction_id' => 'txn-check-1',
                    'gross_amount' => '100000.00',
                ]);
        });

        $response = $this->actingAs($user)->postJson("/siswa/orders/{$order->id}/check-status");

        $response->assertOk();
        $response->assertJson(['status' => 'Siap Diambil']);
        $this->assertSame('Siap Diambil', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_check_status_denied_for_other_users_order(): void
    {
        $owner = $this->makeUser();
        $attacker = $this->makeUser();
        $order = $this->makeOrder($owner);

        $response = $this->actingAs($attacker)->postJson("/siswa/orders/{$order->id}/check-status");

        $response->assertStatus(403);
    }

    public function test_success_page_shows_for_order_owner(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->actingAs($user)->get("/siswa/orders/success/{$order->id}");

        $response->assertOk();
        $response->assertSee($order->order_code);
    }

    public function test_success_page_denied_for_other_user(): void
    {
        $owner = $this->makeUser();
        $attacker = $this->makeUser();
        $order = $this->makeOrder($owner);

        $response = $this->actingAs($attacker)->get("/siswa/orders/success/{$order->id}");

        $response->assertStatus(403);
    }

    public function test_update_status_route_no_longer_exists(): void
    {
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('siswa.orders.updateStatus'));
    }
}
