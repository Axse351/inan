<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pemasoks')->insert([
            [
                'kode_pemasok' => 'SUP001',
                'nama_pemasok' => 'PT Sumber Jaya',
                'alamat'       => 'Jakarta',
                'telepon'      => '081234567890',
                'email'        => 'sumberjaya@email.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'kode_pemasok' => 'SUP002',
                'nama_pemasok' => 'CV Makmur Abadi',
                'alamat'       => 'Bandung',
                'telepon'      => '081234567891',
                'email'        => 'makmur@email.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'kode_pemasok' => 'SUP003',
                'nama_pemasok' => 'PT Sentosa',
                'alamat'       => 'Surabaya',
                'telepon'      => '081234567892',
                'email'        => 'sentosa@email.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'kode_pemasok' => 'SUP004',
                'nama_pemasok' => 'PT Maju Bersama',
                'alamat'       => 'Semarang',
                'telepon'      => '081234567893',
                'email'        => 'maju@email.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'kode_pemasok' => 'SUP005',
                'nama_pemasok' => 'CV Berkah',
                'alamat'       => 'Yogyakarta',
                'telepon'      => '081234567894',
                'email'        => 'berkah@email.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
