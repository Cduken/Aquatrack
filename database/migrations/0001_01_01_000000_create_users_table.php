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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('account_number');
            $table->string('address')->nullable();
            $table->date('date_installed')->nullable();
            $table->string('meter_brand')->nullable();
            $table->string('meter_serial_number')->nullable();
            $table->integer('meter_size')->nullable(); // in mm
            $table->decimal('last_reading', 8, 2)->nullable();
            $table->decimal('last_consumption', 8, 2)->nullable();
            $table->decimal('last_amount_due', 10, 2)->nullable();
            $table->date('last_reading_date')->nullable();
            $table->decimal('consumption', 8, 2)->nullable();
            $table->decimal('amount_due', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->enum('payment_status', ['Paid', 'Pending', 'Overdue'])->default('Pending');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
