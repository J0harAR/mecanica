<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 4; $i++) {
            DB::table('grupo')->insert([
                'clave' => Str::random(10),

            ]);
        }
    }
}
