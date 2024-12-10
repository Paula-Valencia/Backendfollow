<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRegistersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_registers')->insert([
            // Super Administrador
            [
                'identification' => 112233445,
                'name' => 'Carlos',
                'last_name' => 'Martinez',
                'telephone' => 9101,
                'email' => 'superadmin@gmail.com',
                'address' => 'Avenida Siempre Viva 742',
                'department' => 'Cauca',
                'municipality' => 'Popayán',
                'password' => bcrypt('12345678'), // Asegúrate de encriptar la contraseña
                'id_role' => 1, // Suponiendo que 3 es el rol de super administrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Administrador
            [
                'identification' => 987654321,
                'name' => 'Ana',
                'last_name' => 'Gomez',
                'telephone' => 5678,
                'email' => 'admin@gmail.com',
                'address' => 'Calle Falsa 123',
                'department' => 'Cauca',
                'municipality' => 'Popayán',
                'password' => bcrypt('12345678'), // Asegúrate de encriptar la contraseña
                'id_role' => 2, // Suponiendo que 1 es el rol de administrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Aprendiz
            [
                'identification' => 143242112,
                'name' => 'Juan',
                'last_name' => 'Perez',
                'telephone' => 1234,
                'email' => 'aprendiz@gmail.com',
                'address' => 'Hola Mundo',
                'department' => 'Cauca',
                'municipality' => 'Popayán',
                'password' => bcrypt('12345678'), // Asegúrate de encriptar la contraseña
                'id_role' => 4, // Suponiendo que 2 es el rol de aprendiz
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
