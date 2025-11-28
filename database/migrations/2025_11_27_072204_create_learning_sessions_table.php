<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('learning_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users');
            $table->string('judul');
            $table->text('deskripsi');
            $table->foreignId('kategori_id')->constrained('kategories');
            $table->enum('tipe', ['online', 'offline']);
            $table->string('lokasi_offline')->nullable();
            $table->string('link_meeting')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->integer('durasi');
            $table->decimal('harga', 10, 2);
            $table->integer('kuota');
            $table->integer('terisi')->default(0);
            $table->enum('status', ['draft', 'active', 'full', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('learning_sessions');
    }
};