<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AccountOperationsWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_redemption_request_requires_notes()
    {
        $user = User::findOrFail($this->createUser('redeem-request-empty@example.test', 2));

        $this->actingAs($user, 'web')
            ->from('/redeem-request')
            ->post('/sent-request', [
                'beneficiary_id' => 7,
                'notes' => '',
            ])
            ->assertRedirect('/redeem-request')
            ->assertSessionHas('message', 'Please enter bank detail');

        $this->assertSame(0, DB::table('redeem_requests')->where('user_id', $user->id)->count());
    }

    public function test_user_can_submit_a_redemption_request_for_a_saved_beneficiary()
    {
        $user = User::findOrFail($this->createUser('redeem-request-success@example.test', 2));
        $beneficiaryId = $this->createBeneficiary($user);

        $this->actingAs($user, 'web')
            ->from('/redeem-request')
            ->post('/sent-request', [
                'beneficiary_id' => $beneficiaryId,
                'notes' => 'Synthetic redemption request',
            ])
            ->assertRedirect('/redeem-request')
            ->assertSessionHas('sucess_message', 'Redeemption request successfully sent');

        $this->assertDatabaseHas('redeem_requests', [
            'user_id' => $user->id,
            'beneficiary_id' => $beneficiaryId,
            'notes' => 'Synthetic redemption request',
        ]);
    }

    public function test_user_can_view_only_in_app_notifications_and_mark_one_as_read()
    {
        $user = User::findOrFail($this->createUser('notifications-owner@example.test', 2));
        $otherUser = User::findOrFail($this->createUser('notifications-other@example.test', 2));
        $notificationId = $this->createNotification($user->id, 'Visible synthetic notification', 1);
        $this->createNotification($user->id, 'Hidden email-only notification', 0);
        $this->createNotification($otherUser->id, 'Another user notification', 1);

        $this->actingAs($user, 'web')
            ->get('/usernotification')
            ->assertStatus(200)
            ->assertSee('Visible synthetic notification')
            ->assertDontSee('Hidden email-only notification')
            ->assertDontSee('Another user notification');

        $this->postJson('/update-status-notification', ['id' => $notificationId])
            ->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'id' => $notificationId,
            'user_id' => $user->id,
            'is_read' => 1,
        ]);
    }

    private function createBeneficiary(User $user)
    {
        return DB::table('beneficiary')->insertGetId([
            'company_name' => 'Synthetic company',
            'contact_name' => 'Synthetic contact',
            'email' => 'synthetic-beneficiary@example.test',
            'mobile_no' => '0000000000',
            'tax_id' => 'SYNTHETIC-TAX',
            'bank_country' => 'ZZ',
            'currency' => 'USD',
            'bank_account' => 'SYNTHETIC-BENEFICIARY-ACCOUNT',
            'bank_code' => 'SYNTHETIC-BANK-CODE',
            'code_no' => 'SYNTHETIC-CODE-NO',
            'beneficiary_country' => 'ZZ',
            'beneficiary_city' => 'Synthetic City',
            'beneficiary_address' => 'Synthetic Address',
            'user_id' => $user->id,
        ]);
    }

    private function createNotification($userId, $message, $inApp)
    {
        return DB::table('notifications')->insertGetId([
            'user_id' => $userId,
            'message' => $message,
            'link' => '/home',
            'in_app' => $inApp,
            'is_read' => 0,
        ]);
    }
}
