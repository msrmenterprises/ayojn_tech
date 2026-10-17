<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class AdminUserMaintenanceWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_admin_can_update_a_users_email_remark_and_identity()
    {
        $admin = User::findOrFail($this->createUser('admin-user-edit@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('user-edit-target@example.test', 1));

        $this->actingAs($admin, 'controlpanel')
            ->post('/controlpanel/updateuser', [
                'userid' => $user->id,
                'email' => 'user-edit-updated@example.test',
                'remark' => 'Synthetic admin remark',
                'identity' => 'Synthetic organization identity',
            ])
            ->assertRedirect('/controlpanel/user-list')
            ->assertSessionHas('success', 'Profile Updated Successfully');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'user-edit-updated@example.test',
            'remark' => 'Synthetic admin remark',
            'identity' => 'Synthetic organization identity',
        ]);
    }

    public function test_admin_user_update_rejects_an_email_owned_by_another_account()
    {
        $admin = User::findOrFail($this->createUser('admin-user-edit-duplicate@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('user-edit-owner@example.test', 1));
        $otherUser = User::findOrFail($this->createUser('user-edit-other@example.test', 2));

        $this->actingAs($admin, 'controlpanel')
            ->from('/controlpanel/editusers/' . $user->id)
            ->post('/controlpanel/updateuser', [
                'userid' => $user->id,
                'email' => $otherUser->email,
                'remark' => 'This update must not be saved',
                'identity' => 'Synthetic identity',
            ])
            ->assertRedirect('/controlpanel/editusers/' . $user->id)
            ->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'user-edit-owner@example.test',
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'remark' => 'This update must not be saved',
        ]);
    }

    public function test_admin_can_view_and_update_a_users_beneficiary()
    {
        $admin = User::findOrFail($this->createUser('admin-beneficiary@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('beneficiary-owner@example.test', 1));
        $beneficiaryId = DB::table('beneficiary')->insertGetId([
            'company_name' => 'Synthetic beneficiary company',
            'contact_name' => 'Synthetic beneficiary contact',
            'email' => 'beneficiary-before@example.test',
            'mobile_no' => '5550100200',
            'tax_id' => 'SYNTHETIC-TAX-BEFORE',
            'bank_country' => 'ZZ',
            'currency' => 'USD',
            'bank_account' => 'SYNTHETIC-BENEFICIARY-BEFORE',
            'bank_code' => 'SYNTHETIC-BANK-CODE',
            'code_no' => 'SYNTHETIC-CODE-NO',
            'beneficiary_country' => 'ZZ',
            'beneficiary_city' => 'Synthetic City',
            'beneficiary_address' => 'Synthetic Address',
            'user_id' => $user->id,
        ]);

        $this->actingAs($admin, 'controlpanel');
        $editResponse = $this->getJson('/controlpanel/beneficiary-edit-admin/' . $beneficiaryId)
            ->assertStatus(200)
            ->assertJsonStructure(['html']);
        $this->assertStringContainsString(
            'Synthetic beneficiary company',
            json_decode($editResponse->getContent(), true)['html']
        );

        $this->post('/controlpanel/beneficiary-update-admin', [
            'id' => $beneficiaryId,
            'company_name' => 'Synthetic beneficiary company updated',
            'contact_name' => 'Synthetic contact updated',
            'email' => 'beneficiary-after@example.test',
            'mobile_no' => '5550100201',
            'tax_id' => 'SYNTHETIC-TAX-AFTER',
            'bank_country' => 'ZZ',
            'currency' => 'EUR',
            'bank_account' => 'SYNTHETIC-BENEFICIARY-AFTER',
            'bank_code' => 'SYNTHETIC-BANK-CODE-AFTER',
            'code_no' => 'SYNTHETIC-CODE-NO-AFTER',
            'beneficiary_country' => 'ZZ',
            'beneficiary_city' => 'Updated Synthetic City',
            'beneficiary_address' => 'Updated Synthetic Address',
        ])
            ->assertRedirect('/controlpanel/all-beneficiary')
            ->assertSessionHas('success', 'Beneficiary has been update successfully!');

        $this->assertDatabaseHas('beneficiary', [
            'id' => $beneficiaryId,
            'user_id' => $user->id,
            'company_name' => 'Synthetic beneficiary company updated',
            'email' => 'beneficiary-after@example.test',
            'bank_account' => 'SYNTHETIC-BENEFICIARY-AFTER',
            'beneficiary_city' => 'Updated Synthetic City',
        ]);
    }

    public function test_admin_can_view_saved_user_bank_details()
    {
        $admin = User::findOrFail($this->createUser('admin-bank-details@example.test', 0, '000000', [
            'role_id' => 1,
        ]));
        $user = User::findOrFail($this->createUser('bank-details-owner@example.test', 1));
        DB::table('userbankdetails')->insert([
            'user_id' => $user->id,
            'my_bank_country' => 'ZZ',
            'my_currency' => 'USD',
            'my_bank_account' => 'SYNTHETIC-ADMIN-BANK-ACCOUNT',
            'my_bank_code' => 'SYNTHETIC-ADMIN-BANK-CODE',
            'my_code_no' => 'SYNTHETIC-ADMIN-CODE-NO',
            'my_taxid' => 'SYNTHETIC-ADMIN-TAX-ID',
            'default_bank' => 1,
        ]);

        $response = $this->actingAs($admin, 'controlpanel')
            ->getJson('/controlpanel/showbankuser/' . $user->id)
            ->assertStatus(200)
            ->assertJsonStructure(['html']);

        $this->assertStringContainsString(
            'SYNTHETIC-ADMIN-BANK-ACCOUNT',
            json_decode($response->getContent(), true)['html']
        );
    }
}
