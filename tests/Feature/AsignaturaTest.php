<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use App\Models\Practica;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Asignatura;
use App\Models\Persona;
use App\Models\Alumno;
use App\Models\Herramientas;
use Spatie\Permission\Models\Permission;
class AsignaturaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_asignaturas(): void
    {
       
        Artisan::call('migrate');
        $user = User::create([
            'name' => 'Test',
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => Hash::make('Johanar2-'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => 'Johanar2-',
        
        ]);

    
        $permissions = [
            Permission::create(['name' => 'ver-asignaturas']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        

        $response= $this->get(route('asignatura.index'))
        ->assertStatus(200)
        ->assertViewIs('asignatura.index');

    }

    public function  test_create_asignatura():void{

        Artisan::call('migrate');

        $user = User::create([
            'name' => 'Test',
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => Hash::make('Johanar2-'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => 'Johanar2-',
        
        ]);

    
        $permissions = [
            Permission::create(['name' => 'ver-asignaturas']),
            Permission::create(['name' => 'crear-asignatura']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        
        //Creacion correcta de la asignatura
        $data=[
            'nombre'=>"Simulacion",
            'clave'=>"SM"
        ];
        $response = $this->post(route('asignatura.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('asignatura.index'));


        //Caso en que exista una materia con la misma clave
        Asignatura::create([
            'clave'=>"IA",
            'nombre'=>"Inteligencia artificial"

        ]);

        $data=[
            'nombre'=>"Taller 2",
            'clave'=>"IA"
        ];
        $response = $this->post(route('asignatura.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'clave' => 'La clave de la asignatura ya está en uso.',
        ]);

    }




    public function test_edit_asignatura():void{


        Artisan::call('migrate');

        $user = User::create([
            'name' => 'Test',
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => Hash::make('Johanar2-'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => 'Johanar2-',
        
        ]);

    
        $permissions = [
            Permission::create(['name' => 'editar-asignatura']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        //Actualizacion correcta de la asignatura
        Asignatura::create([
            'clave'=>"IA",
            'nombre'=>"Inteligencia artificial"

        ]);
        $asignatura=Asignatura::find("IA");
        $this->assertNotNull($asignatura);

        
        //Actualizacion correcta de la asignatura
        $data=[
            'nombre'=>"Simulacion 2",
        ];

        $response = $this->patch(route('asignatura.update',$asignatura->clave), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('asignatura.index'));

        //Nombre duplicado de la asignatura

        $data=[
            'nombre'=>"Simulacion 2",
        ];

        $response = $this->patch(route('asignatura.update',$asignatura->clave), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nombre' => 'El nombre de la asignatura ya está en uso.',
        ]);

    }

    public function test_delete_asignatura():void{

        Artisan::call('migrate');

        $user = User::create([
            'name' => 'Test',
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => Hash::make('Johanar2-'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => 'Johanar2-',
        
        ]);

    
        $permissions = [
            Permission::create(['name' => 'borrar-asignatura']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        
        Asignatura::create([
            'clave'=>"IA",
            'nombre'=>"Inteligencia artificial"

        ]);
        $asignatura=Asignatura::find("IA");
        $this->assertNotNull($asignatura);
    

        $response=$this->delete(route('asignatura.destroy',$asignatura->clave));
        $response->assertRedirect(route('asignatura.index'));
    }


}
