<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
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

    public function test_siswa_can_upload_profile_photo(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/siswa/profile/photo', [
            'photo' => UploadedFile::fake()->image('foto.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertNotNull($user->fresh()->foto);
        Storage::disk('public')->assertExists($user->fresh()->foto);
    }

    public function test_upload_rejects_non_image_file(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/siswa/profile/photo', [
            'photo' => UploadedFile::fake()->create('dokumen.txt', 10),
        ]);

        $response->assertSessionHasErrors('photo');
        $this->assertNull($user->fresh()->foto);
    }
}
