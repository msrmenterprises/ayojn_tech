<?php

namespace Tests\Feature;

use App\User;
use App\VouchCode;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;

class WalletAccountWorkflowTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesTestUsers;

    public function test_valid_voucher_credits_wallet_once_and_redeemed_code_is_rejected()
    {
        $user = User::findOrFail($this->createUser('wallet-voucher@example.test', 2));
        $user->wallet_balance = 12;
        $user->save();

        $voucher = VouchCode::create([
            'vouch_code' => 'SYNTHETIC-WALLET-CREDIT',
            'vouch_amount' => 25,
            'expeiry_date' => '2035-01-01',
        ]);
        DB::table('vouch_codes')->where('id', $voucher->id)->update(['status' => 0]);

        $this->actingAs($user, 'web')
            ->postJson('/voucher-redeem', ['vouch_code' => $voucher->vouch_code])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'msg' => 'Voucher is valid.',
            ]);

        $this->assertEquals(37, (float) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
        $this->assertDatabaseHas('vouch_codes', [
            'id' => $voucher->id,
            'status' => 1,
            'used_by' => $user->id,
        ]);
        $this->assertDatabaseHas('wallet_logs', [
            'user_id' => $user->id,
            'points_by' => 6,
            'point' => 25,
            'trans_id' => $voucher->id,
            'point_type' => 'CR',
            'wallet_type' => 0,
        ]);

        $this->postJson('/voucher-redeem', ['vouch_code' => $voucher->vouch_code])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'faild',
                'msg' => 'Voucher code is not valid or already redeemed.',
            ]);

        $this->assertEquals(37, (float) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
        $this->assertSame(1, DB::table('wallet_logs')->where('user_id', $user->id)->count());
    }

    public function test_invalid_voucher_does_not_change_wallet_or_create_a_wallet_log()
    {
        $user = User::findOrFail($this->createUser('wallet-invalid-voucher@example.test', 2));

        $this->actingAs($user, 'web')
            ->postJson('/voucher-redeem', ['vouch_code' => 'SYNTHETIC-NOT-VALID'])
            ->assertStatus(200)
            ->assertJson([
                'status' => 'faild',
                'msg' => 'Voucher code is not valid or already redeemed.',
            ]);

        $this->assertEquals(0, (float) DB::table('users')->where('id', $user->id)->value('wallet_balance'));
        $this->assertSame(0, DB::table('wallet_logs')->where('user_id', $user->id)->count());
    }

    public function test_saving_bank_details_keeps_only_the_latest_record_as_default()
    {
        $user = User::findOrFail($this->createUser('bank-details@example.test', 2));
        $this->actingAs($user, 'web');

        $this->postJson('/savebankform', $this->bankDetails('SYNTHETIC-ACCOUNT-1'))
            ->assertStatus(200)
            ->assertJson([
                'status' => 1,
                'msg' => 'Your Record has been save successfully',
            ]);

        $this->postJson('/savebankform', $this->bankDetails('SYNTHETIC-ACCOUNT-2'))
            ->assertStatus(200)
            ->assertJson([
                'status' => 1,
                'msg' => 'Your Record has been save successfully',
            ]);

        $this->assertDatabaseHas('userbankdetails', [
            'user_id' => $user->id,
            'my_bank_account' => 'SYNTHETIC-ACCOUNT-1',
            'default_bank' => 0,
        ]);
        $this->assertDatabaseHas('userbankdetails', [
            'user_id' => $user->id,
            'my_bank_account' => 'SYNTHETIC-ACCOUNT-2',
            'default_bank' => 1,
        ]);
        $this->assertSame(1, DB::table('userbankdetails')
            ->where('user_id', $user->id)
            ->where('default_bank', 1)
            ->count());

        $this->postJson('/mybankget')
            ->assertStatus(200)
            ->assertJsonFragment(['my_bank_account' => 'SYNTHETIC-ACCOUNT-2']);
    }

    private function bankDetails($accountNumber)
    {
        return [
            'my_bank_country' => 'ZZ',
            'my_currency' => 'USD',
            'my_bank_account' => $accountNumber,
            'my_bank_code' => 'SYNTHETIC-BANK-CODE',
            'my_code_no' => 'SYNTHETIC-CODE-NO',
            'my_taxid' => 'SYNTHETIC-TAX-ID',
        ];
    }
}
