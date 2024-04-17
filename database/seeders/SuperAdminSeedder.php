<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class SuperAdminSeedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $usuario= User::create([
            'name'=>'Pimentel',
            'email'=>'pimente@gmail.com',
            'password'=>bcrypt('1234'),
        ]);
        
        //En caso de que no haya ningun rol registrado
        $rol=Role::create(['name'=>'Administrador']);
        $permisos=Permission::pluck('id','id')->all();
        $rol->syncPermissions($permisos);

        
        $usuario->assignRole('Administrador');

    }
}
