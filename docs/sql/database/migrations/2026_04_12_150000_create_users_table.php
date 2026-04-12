<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 191)->unique('uq_users_email');
            $table->string('password');
            $table->string('role', 30)->default('member')->comment('member|editor|admin');
            $table->string('status', 30)->default('active')->comment('active|inactive|suspended');
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['role', 'status'], 'idx_users_role_status');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('users');

    }
};
