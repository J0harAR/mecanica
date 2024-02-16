<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//Spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos=[
            //Usuarios       
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'borrar-usuarios',
            //Roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',

        ];
        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
            

        }
    }
}
