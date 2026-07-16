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
        Schema::create('detail_do_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_do_store')
                ->constrained('do_stores')
                ->cascadeOnDelete();
            $table->string('produk');
            $table->integer('qty');
            $table->string('satuan');
            $table->integer('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_do_stores');
    }
};
