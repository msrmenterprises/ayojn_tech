<?php

namespace Tests\Feature;

use App\Models\NotificationSetting;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class NotificationSettingsWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_user_can_view_and_update_notification_settings()
    {
        $user = User::findOrFail($this->createUser('notifications@example.test', 1));
        $setting = NotificationSetting::create([
            'user_id' => $user->id,
            'inapp' => 1,
            'email' => 0,
            'days' => null,
        ]);

        $this->actingAs($user, 'web')
            ->get('/notification-settings')
            ->assertStatus(200);

        $response = $this->post('/set-notifications', [
            'id' => $setting->id,
            'inApp' => 1,
            'emailNotification' => 1,
            'days' => '2',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Notification settings successfully updated');
        $this->assertSame(1, (int) DB::table('notification_settings')->where('id', $setting->id)->value('inapp'));
        $this->assertSame(1, (int) DB::table('notification_settings')->where('id', $setting->id)->value('email'));
        $this->assertSame('2', DB::table('notification_settings')->where('id', $setting->id)->value('days'));
    }

    public function test_update_reports_missing_notification_settings()
    {
        $user = User::findOrFail($this->createUser('missing-notifications@example.test', 1));

        $response = $this->actingAs($user, 'web')->post('/set-notifications', [
            'id' => 999999,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
