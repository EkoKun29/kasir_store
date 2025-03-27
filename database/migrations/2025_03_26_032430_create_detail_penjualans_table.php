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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barcode_id');
            $table->unsignedBigInteger('pcs')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->unsignedBigInteger('diskon')->nullable();
            $table->unsignedBigInteger('penjualan_id');
            $table->timestamps();
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');
            $table->foreign('barcode_id')->references('id')->on('barcodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
