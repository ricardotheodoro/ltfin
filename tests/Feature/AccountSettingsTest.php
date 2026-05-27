<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_account_settings_data(): void
    {
        // Arrange
        $user = User::factory()->create([
            'first_name' => 'Nome Atual',
            'last_name' => 'Sobrenome Atual',
        ]);

        // Act
        $response = $this->actingAs($user)->put(route('settings.update'), [
            'first_name' => 'Nome Novo',
            'last_name' => 'Sobrenome Novo',
            'company' => 'LTFin',
            'phone' => '11999999999',
            'website' => 'https://ltfin.app',
            'country' => 'br',
            'language' => 'pt',
            'timezone' => 'America/Sao_Paulo',
            'currency' => 'BRL',
            'communication' => [
                'email' => '1',
                'phone' => '0',
            ],
            'marketing' => '1',
            'avatar_remove' => '0',
        ]);

        // Assert
        $response->assertRedirect('account/settings');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Nome Novo',
            'last_name' => 'Sobrenome Novo',
        ]);

        $this->assertDatabaseHas('user_infos', [
            'user_id' => $user->id,
            'company' => 'LTFin',
            'phone' => '11999999999',
            'website' => 'https://ltfin.app',
            'country' => 'br',
            'language' => 'pt',
            'timezone' => 'America/Sao_Paulo',
            'currency' => 'BRL',
            'marketing' => 1,
        ]);

        $user->refresh();
        $this->assertSame('1', (string) ($user->info->communication['email'] ?? ''));
        $this->assertSame('0', (string) ($user->info->communication['phone'] ?? ''));
    }

    public function test_authenticated_user_can_upload_and_replace_avatar(): void
    {
        // Arrange
        Storage::fake('public');

        $user = User::factory()->create();

        Storage::disk('public')->put('images/old-avatar.jpg', 'old-avatar-content');

        UserInfo::query()->insert([
            'user_id' => $user->id,
            'avatar' => 'images/old-avatar.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $newAvatar = UploadedFile::fake()->image('new-avatar.jpg');

        // Act
        $response = $this->actingAs($user)->put(route('settings.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar' => $newAvatar,
            'avatar_remove' => '0',
        ]);

        // Assert
        $response->assertRedirect('account/settings');

        $user->refresh();
        $this->assertNotNull($user->info);
        $this->assertNotNull($user->info->avatar);
        Storage::disk('public')->assertMissing('images/old-avatar.jpg');
        Storage::disk('public')->assertExists($user->info->avatar);
    }

    public function test_authenticated_user_can_remove_avatar(): void
    {
        // Arrange
        Storage::fake('public');

        $user = User::factory()->create();
        Storage::disk('public')->put('images/remove-avatar.jpg', 'avatar-content');

        UserInfo::query()->insert([
            'user_id' => $user->id,
            'avatar' => 'images/remove-avatar.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->put(route('settings.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar_remove' => '1',
        ]);

        // Assert
        $response->assertRedirect('account/settings');

        $user->refresh();
        $this->assertNull($user->info->avatar);
        Storage::disk('public')->assertMissing('images/remove-avatar.jpg');
    }
}
