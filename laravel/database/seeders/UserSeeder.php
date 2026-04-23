<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Credenciales de desarrollo (local)
|--------------------------------------------------------------------------
|
| admin@jass.pe    | password  | role=admin
| operador@jass.pe | password  | role=operador
|
| Estas credenciales son SOLO para entorno de desarrollo. En producción
| deben reemplazarse por contraseñas seguras generadas manualmente.
|
*/
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@jass.pe',
                'role' => 'admin',
            ],
            [
                'name' => 'Operador JASS',
                'email' => 'operador@jass.pe',
                'role' => 'operador',
            ],
        ];

        foreach ($usuarios as $usuario) {
            DB::table('users')->updateOrInsert(
                ['email' => $usuario['email']],
                [
                    'name' => $usuario['name'],
                    'password' => Hash::make('password'),
                    'role' => $usuario['role'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
