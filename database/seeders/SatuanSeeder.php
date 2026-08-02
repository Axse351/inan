<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuans')->insert([
            ['nama_satuan' => 'PCS'],
            ['nama_satuan' => 'BOX'],
            ['nama_satuan' => 'PACK'],
            ['nama_satuan' => 'LUSIN'],
            ['nama_satuan' => 'KG'],
        ]);
    }
}
