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
use App\Models\Persona;
use App\Models\Periodo;
use App\Models\Alumno;
use App\Models\Asignatura;
use Spatie\Permission\Models\Permission;
class GrupoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_grupos(): void
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
            Permission::create(['name' => 'ver-grupos']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        

        $response= $this->get(route('grupos.index'))
        ->assertStatus(200)
        ->assertViewIs('grupos.index');
    }

    public function test_create_grupos():void{
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
            Permission::create(['name' => 'ver-grupos']),
            Permission::create(['name' => 'crear-grupo']),
           
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        

        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        $asignatura=Asignatura::find('IA');
        $this->assertNotNull($asignatura);
       
        $data=[
            'clave_grupo'=>"1A",
            'asignatura'=>$asignatura->clave,
            'periodo'=>"2024-3"
        ];

        $response = $this->post(route('grupos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));


        //Caso de grupo duplicado con misma asignatura
        Grupo::create([
            'id_docente'=>null,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>"2024-3"
        ]);

        $data=[
            'clave_grupo'=>"IA1",
            'asignatura'=>$asignatura->clave,
            'periodo'=>"2024-3"
        ];

        $response = $this->post(route('grupos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
        $response->assertSessionHas('error');

        //Caso que no se ponga el grupo 

        $data=[
            'clave_grupo'=>null,
            'asignatura'=>$asignatura->clave,
            'periodo'=>"2024-3"
        ];

        $response = $this->post(route('grupos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'clave_grupo' => 'La clave del grupo es obligatoria.',
        ]);

    }


    public function test_delete_grupo():void{
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
            Permission::create(['name' => 'ver-grupos']),
            Permission::create(['name' => 'borrar-grupo']),
           
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);

        Periodo::create([
            
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        Grupo::create([
            'id_docente'=>null,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>"IA",
            'clave_periodo'=>'2024-3'
        ]);

        $response = $this->delete(route('grupos.destroy',"IA1")); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));


        //Eliminar grupo con alumnos 

        Persona::create([
            'curp'=>"OOAZ900824MTSRLL08",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);


        
        Grupo::create([
            'id_docente'=>null,
            'clave_grupo'=>"IA22",
            'clave_asignatura'=>"IA",
            'clave_periodo'=>'2024-3'
        ]);

        $alumno=Alumno::create([
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL08",
          ]);

          $alumno->grupos()->attach([
            'clave_grupo' => 1
        ], [
            'id_alumno' => '19161299'
        ]);


        $response = $this->delete(route('grupos.destroy',1)); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
        $this->assertDatabaseMissing('alumno_grupo', ['clave_grupo' =>"IA22"]);


    }
}
