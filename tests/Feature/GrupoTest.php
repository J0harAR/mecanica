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

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        $asignatura=Asignatura::find('IA');
        $this->assertNotNull($asignatura);
       
        $data=[
            'clave_grupo'=>"1A",
            'asignatura'=>$asignatura->clave
        ];

        $response = $this->post(route('grupos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
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

        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'clave_grupo'=>"B1",
            'clave_asignatura'=>$asignatura->clave,
        ]);

        $response = $this->delete(route('grupos.destroy',"B1")); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));


        //Eliminar grupo con alumnos 
        Persona::create([
            'curp'=>"AAAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"AAAA",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

        Asignatura::create([
            'clave'=>'IAR',
            'nombre'=>'Inteligencia artificial'
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IAR');



        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');
        $this->assertNotNull($grupo);

        $data=[
            'no_control'=>"19161299",
            'curp'=>"AAAAR",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'grupos'=>[
                $grupo->clave_grupo
            ]

        ];
        $response = $this->post(route('alumnos.store'), $data); 
       


        $response = $this->delete(route('grupos.destroy',$grupo->clave_grupo)); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
        
        $this->assertDatabaseMissing('alumno_grupo', ['clave_grupo' => $grupo->clave_grupo]);


    }
}
