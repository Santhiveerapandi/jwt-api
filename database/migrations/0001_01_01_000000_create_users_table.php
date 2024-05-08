<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name', 40);
            $table->string('email', 50)->unique();
            $table->string('employee_code', 10)->nullable();
            $table->bigInteger('contact_no')->unsigned();
            $table->string('designation', 100)->nullable();
            $table->string('skill', 100);
            $table->decimal('total_experience', total: 3, places: 1);
            $table->decimal('relevant_experience', total: 3, places: 1);
            $table->decimal('current_ctc', total:10, places: 2);
            $table->decimal('expected_ctc', total:10, places: 2);
            $table->string('last_reason_resignation', 200);
            $table->string('location', 100);
            $table->string('notice_period', 50);
            $table->string('interview_cri', 5)->comment('Last Interview attended in past 6 months')->nullable();
            $table->string('acquaintances_cri', 5)->nullable();
            $table->json('family_background')->nullable();
            $table->string('profile', 300);
            $table->timestamp('doj')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('usertype')->default('employee');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');        
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
