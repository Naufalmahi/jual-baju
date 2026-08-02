<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
            'nisn_nip' => 'NISN' . Str::random(8),
            'username' => 'siswa_' . Str::random(6),
        ]);
    }

    private function makeOrder(User $user, float $amount = 100000): Order
    {
        return Order::create([
            'order_code' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'total_amount' => $amount,
            'payment_method' => 'qris',
            'status' => 'Menunggu Pembayaran',
        ]);
    }

    private function notification(array $overrides = []): array
    {
        $base = [
            'order_id' => 'ORD-TEST',
            'status_code' => '200',
            'gross_amount' => '100000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'txn-123456',
            'payment_type' => 'qris',
            'fraud_status' => 'accept',
        ];

        $data = array_merge($base, $overrides);
        $data['signature_key'] = hash(
            'sha512',
            $data['order_id'] . $data['status_code'] . $data['gross_amount'] . config('midtrans.server_key')
        );

        return $data;
    }

    public function test_orders_table_has_midtrans_transaction_id_column(): void
    {
        $this->assertTrue(Schema::hasColumn('orders', 'midtrans_transaction_id'));
    }

    public function test_valid_settlement_marks_order_ready_and_saves_transaction_id(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->postJson('/api/orders/webhook', $this->notification([
            'order_id' => $order->order_code,
        ]));

        $response->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Siap Diambil',
            'midtrans_transaction_id' => 'txn-123456',
        ]);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_webhook_with_invalid_signature_is_rejected(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $payload = $this->notification(['order_id' => $order->order_code]);
        $payload['signature_key'] = 'wrong-signature';

        $response = $this->postJson('/api/orders/webhook', $payload);

        $response->assertStatus(403);
        $this->assertSame('Menunggu Pembayaran', $order->fresh()->status);
    }

    public function test_webhook_with_mismatched_gross_amount_is_rejected(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user, 100000);

        $response = $this->postJson('/api/orders/webhook', $this->notification([
            'order_id' => $order->order_code,
            'gross_amount' => '99999.00',
        ]));

        $response->assertStatus(403);
        $this->assertSame('Menunggu Pembayaran', $order->fresh()->status);
    }

    public function test_capture_with_challenge_fraud_status_does_not_mark_paid(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->postJson('/api/orders/webhook', $this->notification([
            'order_id' => $order->order_code,
            'transaction_status' => 'capture',
            'fraud_status' => 'challenge',
        ]));

        $response->assertOk();
        $this->assertSame('Menunggu Pembayaran', $order->fresh()->status);
    }

    public function test_pending_keeps_order_waiting_for_payment(): void
    {
        $user = $this->makeUser();
        $order = $this->makeOrder($user);

        $response = $this->postJson('/api/orders/webhook', $this->notification([
            'order_id' => $order->order_code,
            'transaction_status' => 'pending',
        ]));

        $response->assertOk();
        $this->assertSame('Menunggu Pembayaran', $order->fresh()->status);
    }

    public function test_failed_statuses_mark_order_cancelled(): void
    {
        foreach (['deny', 'cancel', 'expire', 'refund'] as $status) {
            $user = $this->makeUser();
            $order = $this->makeOrder($user);

            $response = $this->postJson('/api/orders/webhook', $this->notification([
                'order_id' => $order->order_code,
                'transaction_status' => $status,
            ]));

            $response->assertOk();
            $this->assertSame('Dibatalkan', $order->fresh()->status, "status: {$status}");
        }
    }

    public function test_webhook_for_unknown_order_returns_404(): void
    {
        $response = $this->postJson('/api/orders/webhook', $this->notification([
            'order_id' => 'ORD-NOT-EXIST',
        ]));

        $response->assertStatus(404);
    }
}
