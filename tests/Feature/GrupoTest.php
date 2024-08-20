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

class GrupoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_grupos(): void
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
        

        $response= $this->get(route('grupos.index'))
        ->assertStatus(200)
        ->assertViewIs('grupos.index');
    }

    public function test_create_grupos():void{
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
       
        $response= $this->get(route('grupos.create'))
        ->assertStatus(200)
        ->assertViewIs('grupos.create');
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


        //Caso de grupo duplicado
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

    }


    

    public function test_delete_grupo():void{
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
            'clave_grupo' => 'IA22'
        ], [
            'id_alumno' => '19161299'
        ]);


        $response = $this->delete(route('grupos.destroy','IA22')); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
        $this->assertDatabaseMissing('alumno_grupo', ['clave_grupo' =>"IA22"]);


    }
}
