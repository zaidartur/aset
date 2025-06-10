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
        Schema::create('master_subdatas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid_subdata', '40')->unique();
            $table->string('kode_subdata', '25')->unique();
            $table->string('parent', '25');
            $table->string('uraian', '100');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_subdatas');
    }
};
