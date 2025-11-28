<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
// database/seeders/DatabaseSeeder.php
public function run()
{
    // Data Jurusan UMKU
    \App\Models\Jurusan::create(['nama_jurusan' => 'Teknik Informatika', 'kode' => 'TI']);
    \App\Models\Jurusan::create(['nama_jurusan' => 'Sistem Informasi', 'kode' => 'SI']);
    \App\Models\Jurusan::create(['nama_jurusan' => 'Manajemen', 'kode' => 'MNJ']);
    \App\Models\Jurusan::create(['nama_jurusan' => 'Akuntansi', 'kode' => 'AKT']);

    // Data Kategori Les - PASTIKAN pake model yang benar
    \App\Models\Kategori::create(['nama_kategori' => 'Pemrograman', 'deskripsi' => 'Les pemrograman dasar hingga advanced']);
    \App\Models\Kategori::create(['nama_kategori' => 'Matematika', 'deskripsi' => 'Les matematika dan kalkulus']);
    \App\Models\Kategori::create(['nama_kategori' => 'Bahasa Inggris', 'deskripsi' => 'Les bahasa Inggris untuk semua level']);
    \App\Models\Kategori::create(['nama_kategori' => 'Desain Grafis', 'deskripsi' => 'Les desain dan multimedia']);

    // Create Admin User
    \App\Models\User::create([
        'name' => 'Admin Udimify',
        'email' => 'admin@udimify.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'status' => 'active'
    ]);
}}