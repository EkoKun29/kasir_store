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
        Schema::table('barcodes', function (Blueprint $table) {
            $table->string('produk')->nullable()->change();
            $table->date('tanggal_beli')->nullable()->change();
            $table->string('harga_beli')->nullable()->change();
            $table->string('qty')->nullable()->change();
            $table->string('hpp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->string('produk')->nullable(false)->change();
            $table->date('tanggal_beli')->nullable(false)->change();
            $table->string('harga_beli')->nullable(false)->change();
            $table->string('qty')->nullable(false)->change();
            $table->string('hpp')->nullable(false)->change();
        });
    }
};
