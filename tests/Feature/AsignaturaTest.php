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

class AsignaturaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_asignaturas(): void
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
        

        $response= $this->get(route('asignatura.index'))
        ->assertStatus(200)
        ->assertViewIs('asignatura.index');

    }

    public function  test_create_asignatura():void{

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
        

        $response= $this->get(route('asignatura.create'))
        ->assertStatus(200)
        ->assertViewIs('asignatura.create');

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
        $response->assertRedirect(route('asignatura.index'));
        $response->assertSessionHas('error',"Clave de la asignatura duplicada");

    }




    public function test_edit_asignatura():void{


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

        Asignatura::create([
            'clave'=>"IA",
            'nombre'=>"Inteligencia artificial"

        ]);
        $asignatura=Asignatura::find("IA");
        $this->assertNotNull($asignatura);


        $acceso->assertStatus(302)->assertRedirect(route('home'));
        $response =$this->get(route('asignatura.edit',$asignatura->clave))
        ->assertStatus(200)
        ->assertViewIs('asignatura.editar');

        $data=[
            'nombre'=>"Simulacion 2",
        ];

        $response = $this->patch(route('asignatura.update',$asignatura->clave), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('asignatura.index'));


    }

    public function test_delete_asignatura():void{

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
            'clave'=>"IA",
            'nombre'=>"Inteligencia artificial"

        ]);
        $asignatura=Asignatura::find("IA");
        $this->assertNotNull($asignatura);
    

        $response=$this->delete(route('asignatura.destroy',$asignatura->clave));
        $response->assertRedirect(route('asignatura.index'));
    }


}
