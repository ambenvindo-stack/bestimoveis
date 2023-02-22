<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tipo::create(['nome' => 'Apartamento']);
        Tipo::create(['nome' => 'Casa']);
        Tipo::create(['nome' => 'Ponto Comercial']);
    }
}
