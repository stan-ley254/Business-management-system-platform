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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
             $table->foreignId('business_id')->constrained()->onDelete('cascade');

    $table->string('phone');
    $table->string('checkout_request_id')->nullable();
    $table->string('merchant_request_id')->nullable();
    $table->string('response_code')->nullable();
    $table->string('response_description')->nullable();

    $table->decimal('amount', 10, 2);
    $table->enum('transaction_status', ['pending', 'success', 'failed'])->default('pending');
    $table->json('raw_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
