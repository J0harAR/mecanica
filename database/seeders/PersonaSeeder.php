<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            DB::table('persona')->insert([
                'curp' => Str::random(10),
                'nombre' => Str::random(10),
                'apellido_P' => Str::random(10),
                'apellido_m' => Str::random(10),
            ]);
        }

      
    }
}
