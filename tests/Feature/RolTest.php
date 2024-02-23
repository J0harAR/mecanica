<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RolTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_roles(): void
    {
        Artisan::call('migrate');
        
        User::create([
            "name" =>"Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);

        $acceso = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        
        ]);
        $acceso->assertStatus(302)->assertRedirect(route('home'));
        //Ver la tabla de roles
        $acceso = $this->get(route('roles.index'))
        ->assertStatus(200)
        ->assertViewIs('roles.index');

    }


    public function test_create_rol(): void
    {
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);     
        $user->assignRole($roleAdministrador);
       

        $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        ]);
        //Ver formulario de crear rol
        $this->get(route('roles.create'))
        ->assertStatus(200)
        ->assertViewIs('roles.crear');

        //Creacion correcta de un rol 
        $permission1 = Permission::create(['name' => 'permission1']);
        $permission2 = Permission::create(['name' => 'permission2']);;
      
        $this->post(route('roles.store'), [
            'name' => 'Servicio social',
            'permission' => [$permission1->name, $permission2->name],
           
        ])->assertRedirect(route('roles.index'));

    }
    public function test_edit_rol(){
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);
        $roleServicioSocial = Role::firstOrCreate(['name' => 'Servicio Social']);
        $user->assignRole($roleAdministrador);
       

        $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        ]);

        $permission1 = Permission::create(['name' => 'permission1']);
        $permission2 = Permission::create(['name' => 'permission2']);;

        //Ver formulario de editar rol
        $this->get(route('roles.edit',$roleServicioSocial->id))
        ->assertStatus(200)
        ->assertViewIs('roles.editar');
        //Editar rol de forma correcta
        $updateCorrecto = $this->patch(route('roles.update',$roleServicioSocial->id), [
            'name' => 'Residente',
            'permission' => [$permission2->name],
            ])->assertRedirect(route('roles.index'));
        
    }

    public function test_delete_rol(){
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);
        $roleServicioSocial = Role::firstOrCreate(['name' => 'Servicio Social']);
        $user->assignRole($roleAdministrador);
       

        $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        ]);
        
        //Eliminar rol de forma correcta 
        $this->delete(route('roles.destroy', $roleServicioSocial->id))
        ->assertRedirect(route('roles.index'));

        $this->assertDatabaseMissing('roles', ['id' => $roleServicioSocial->id]);
    }


}
