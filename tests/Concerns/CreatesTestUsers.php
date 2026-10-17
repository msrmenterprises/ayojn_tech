<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait CreatesTestUsers
{
    protected function createUser($email, $sponsorType, $otp = '000000', array $attributes = [])
    {
        if (! DB::table('countries')->where('country_code', 'ZZ')->exists()) {
            DB::table('countries')->insert([
                'country_code' => 'ZZ',
                'country_name' => 'Synthetic Test Country',
            ]);
        }

        $userTypeId = DB::table('user_type')->insertGetId([
            'user_type' => 'Synthetic regression user',
            'refer_amount' => 0,
        ]);

        return DB::table('users')->insertGetId(array_merge([
            'name' => 'Regression Test User',
            'email' => $email,
            'password' => Hash::make('valid-password'),
            'gender' => 'Male',
            'sponsor_type' => (string) $sponsorType,
            'country' => 'ZZ',
            'wallet_balance' => '0',
            'wallet_marketplace' => '0',
            'email_verified' => 1,
            'role_id' => 2,
            'userstatus' => 1,
            'is_suspend' => 0,
            'is_kyc' => 0,
            'free_response' => 0,
            'is_paid' => 0,
            'free_vouch' => 0,
            'edit_count' => 0,
            'is_edited' => 0,
            'user_type' => $userTypeId,
            'otp' => $otp,
        ], $attributes));
    }
}
