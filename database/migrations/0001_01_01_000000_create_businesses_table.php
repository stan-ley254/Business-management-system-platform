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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
             $table->string('type')->default('pos'); // 'pos' or 'service'
            $table->date('next_payment_due')->nullable();
        $table->boolean('is_active')->default(true);
            $table->string('mpesa_short_code')->nullable();
            $table->text('mpesa_consumer_key')->nullable();
            $table->text('mpesa_consumer_secret')->nullable();
            $table->text('mpesa_passkey')->nullable();
            $table->string('mpesa_initiator_name')->nullable();
            $table->text('mpesa_security_credential')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
