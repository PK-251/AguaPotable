<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PadronUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('padron_usuarios')->insert([
        ['codigo'=>'U001','nombre'=>'Juan','apellido'=>'Quispe','direccion'=>'Jr. Lima 101','estado'=>'activo','tarifa_id'=>1,'created_at'=>now(),'updated_at'=>now()],
        ['codigo'=>'U002','nombre'=>'Maria','apellido'=>'Huanca','direccion'=>'Jr. Ayacucho 205','estado'=>'activo','tarifa_id'=>1,'created_at'=>now(),'updated_at'=>now()],
        ['codigo'=>'U003','nombre'=>'Pedro','apellido'=>'Flores','direccion'=>'Jr. Cusco 310','estado'=>'activo','tarifa_id'=>1,'created_at'=>now(),'updated_at'=>now()],
    ]);
    }
}
