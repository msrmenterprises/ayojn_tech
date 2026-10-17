<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfaDemoDeliveryTables extends Migration
{
    public function up()
    {
        Schema::create('mfa_demo_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient_email');
            $table->timestamps();
        });

        Schema::create('mfa_demo_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfa_demo_users');
        Schema::dropIfExists('mfa_demo_settings');
    }
}
