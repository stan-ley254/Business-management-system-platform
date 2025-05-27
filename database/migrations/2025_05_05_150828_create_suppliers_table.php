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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('phone_number')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('status')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('business_id')->after('id')->constrained('businesses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
