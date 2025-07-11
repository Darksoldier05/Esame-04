<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConfigurazioniSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('configurazioni')->insert([
            [
                'chiave' => 'maxLoginErrati',
                'valore' => '5',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'chiave' => 'durataSfida',
                'valore' => '120', // 2 minuti
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'chiave' => 'durataSessione',
                'valore' => '1200000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'chiave' => 'storicoPsw',
                'valore' => '3',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
