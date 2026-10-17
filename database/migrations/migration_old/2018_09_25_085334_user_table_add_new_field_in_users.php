<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTableAddNewFieldInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('phone_no')->after('password')->nullable();
            $table->string('company_name')->after('phone_no')->nullable();
            $table->string('profile_pic')->after('company_name')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address')->after('gender')->nullable();
            $table->text('about_me')->after('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
