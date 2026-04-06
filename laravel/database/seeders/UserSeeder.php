<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('users')->insert([
        ['name'=>'Administrador','email'=>'admin@jass.pe','password'=>bcrypt('admin123'),'role'=>'admin','created_at'=>now(),'updated_at'=>now()],
        ['name'=>'Operador JASS','email'=>'operador@jass.pe','password'=>bcrypt('operador123'),'role'=>'operador','created_at'=>now(),'updated_at'=>now()],
    ]);
}
}
