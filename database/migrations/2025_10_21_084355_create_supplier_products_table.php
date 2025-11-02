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
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
             $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
    $table->foreignId('business_id')->constrained()->onDelete('cascade');
    $table->string('supplier_product_name');
    $table->string('barcode')->nullable();
    $table->decimal('default_cost_price', 10, 2)->nullable();
    $table->text('description')->nullable();
    $table->foreignId('linked_product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
