<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('tarifas')->insert([
        'nombre' => 'Tarifa Mensual JASS QUILCATA',
        'monto'  => 4.00,
        'descripcion' => 'Cuota mensual del servicio de agua potable',
        'vigente_desde' => '2024-01-01',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }
}
