<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
       // Insertar departamentos
       $departmentsPath = database_path('seeders/Sql/Departments.sql');
       $departmentsSql = File::get($departmentsPath);
       DB::unprepared($departmentsSql);
       $this->command->info('Departamentos insertados desde el archivo SQL.');

        // Mensaje opcional para verificar que se ejecutó correctamente
        // $this->command->info('Departamentos insertados desde el archivo SQL.');
    }
}
