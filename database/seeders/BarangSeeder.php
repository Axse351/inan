<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        $namaBarang = [
            'Pensil 2B',
            'Pulpen Hitam',
            'Pulpen Biru',
            'Penghapus',
            'Buku Tulis',
            'Buku Gambar',
            'Spidol Hitam',
            'Spidol Merah',
            'Kertas HVS A4',
            'Kertas HVS F4',
            'Map Plastik',
            'Map Kertas',
            'Stapler',
            'Isi Stapler',
            'Gunting',
            'Lem Kertas',
            'Lem Cair',
            'Lakban Bening',
            'Lakban Coklat',
            'Flashdisk 32GB',
            'Mouse Wireless',
            'Keyboard USB',
            'Kabel HDMI',
            'Kabel LAN',
            'Stop Kontak',
            'Lampu LED',
            'Tinta Printer',
            'Toner Printer',
            'Monitor 24 Inch',
            'Printer Inkjet'
        ];

        $merk = [
            'Faber Castell',
            'Joyko',
            'Standard',
            'Kenko',
            'Sidu',
            'PaperOne',
            'Canon',
            'Epson',
            'Logitech',
            'HP'
        ];

        for ($i = 1; $i <= 30; $i++) {
            $data[] = [
                'kode_barang'   => 'BRG' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'barcode'       => '8991000000' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kategori'      => ['ATK', 'Elektronik', 'Peralatan'][array_rand(['ATK', 'Elektronik', 'Peralatan'])],
                'satuan_id'     => rand(1, 3), // Pastikan tabel satuans memiliki ID 1-3
                'pemasok_id'    => rand(1, 5), // Pastikan tabel pemasoks memiliki ID 1-5
                'nama_barang'   => $namaBarang[$i - 1],
                'merk'          => $merk[array_rand($merk)],
                'harga_beli'    => rand(5000, 500000),
                'harga_jual'    => rand(10000, 700000),
                'stok'          => rand(10, 200),
                'stok_minimum'  => rand(5, 20),
                'lokasi_rak'    => 'Rak ' . chr(rand(65, 68)) . '-' . rand(1, 10),
                'deskripsi'     => 'Data barang ' . $namaBarang[$i - 1],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('barangs')->insert($data);
    }
}
