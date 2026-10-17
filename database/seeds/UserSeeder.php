<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(123456),
            'phone_no' =>123456,
            'company_name' =>'test',
            'gender' =>'Male',
            'role_id' =>1,
        ]);

        \App\User::insert([
            'name' => 'Jane Doe',
            'email' => 'jane@doe.com',
            'password' => Hash::make(123456),
            'phone_no' =>1234567,
            'company_name' =>'test',
            'gender' =>'Male',
            'role_id' =>0,
        ]);
    }
}
