<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('taman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->integer('kapasitas');
            $table->decimal('harga_per_hari', 10, 2);
            $table->text('fasilitas');
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true); // true = tersedia
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('taman');
    }
}; 