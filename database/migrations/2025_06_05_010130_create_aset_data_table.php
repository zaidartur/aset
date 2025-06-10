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
        Schema::create('aset_data', function (Blueprint $table) {
            $table->id();
            $table->string('uuid_barang', '40')->unique();
            $table->string('kode_parent', '20');
            $table->string('kode_utama', '20');
            $table->string('kode_urut', '5');
            $table->string('uraian', '40');
            $table->string('nama_barang', '100');
            $table->string('merek_barang', '100')->nullable();
            $table->string('type_barang', '100')->nullable();
            $table->string('ukuran_barang', '40')->nullable();
            $table->string('bahan', '40');
            $table->integer('harga_beli')->default('0');
            $table->string('tahun_beli', '4');
            $table->string('lokasi', '40');
            $table->string('kondisi_barang', '5');
            $table->string('keterangan')->nullable();
            $table->string('user_id', '40');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_data');
    }
};
