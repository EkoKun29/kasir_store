<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->integer('hpp')->after('qty')->nullable();
        });
    }

    public function down()
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropColumn('hpp');
        });
    }
};
