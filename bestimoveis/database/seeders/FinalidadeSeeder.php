<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Finalidade;

class FinalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Finalidade::create(['nome' => 'Aluguel']);
        Finalidade::create(['nome' => 'Venda']);
    }
}
