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
        Schema::create('do_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_do');
            $table->integer('id_user');
            $table->string('lokasi');
            $table->string('penginput');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_stores');
    }
};
