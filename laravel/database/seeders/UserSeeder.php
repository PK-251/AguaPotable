<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/*
|--------------------------------------------------------------------------
| Credenciales de desarrollo (local)
|--------------------------------------------------------------------------
|
| admin@jass.pe    | password  | role=admin
| operador@jass.pe | password  | role=operador
|
| La contraseña se guarda vía modelo User (cast `hashed`). Ejecutar:
|   php artisan db:seed --class=UserSeeder --force
|
*/
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@jass.pe',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Operador JASS',
                'email' => 'operador@jass.pe',
                'password' => 'password',
                'role' => 'operador',
            ],
        ];

        foreach ($usuarios as $attrs) {
            User::updateOrCreate(
                ['email' => $attrs['email']],
                [
                    'name' => $attrs['name'],
                    'password' => $attrs['password'],
                    'role' => $attrs['role'],
                ]
            );
        }
    }
}
