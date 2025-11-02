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
        Schema::create('supplier_invoice_items', function (Blueprint $table) {
            $table->id();
              $table->foreignId('supplier_invoice_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
    $table->integer('quantity')->default(0);
    $table->decimal('cost_price', 15, 2);
    $table->decimal('subtotal', 15, 2)->default(0.00);
    $table->foreignId('business_id')->constrained()->onDelete('cascade');
    $table->foreignId('supplier_product_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_items');
    }
};
