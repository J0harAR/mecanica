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
use App\Models\Alumno;
use App\Models\Asignatura;

class AlumnoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_alumnos(): void
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
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

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



        $response= $this->get(route('alumnos.index'))
        ->assertStatus(200)
        ->assertViewIs('alumnos.index');

    }


    public function test_create_alumno():void{
        
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

       
        $data=[
            'no_control'=>"19161229",
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];


        
        $acceso->assertStatus(302)->assertRedirect(route('home'));

        $response = $this->post(route('alumnos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));

        //Caso en el que el curpo le pertenezca a un docente  la restriccion de inclusion
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

        $data=[
            'no_control'=>"19161230",
            'curp'=>"AAAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];


        $response = $this->post(route('alumnos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        //Registro correcto con grupos
        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

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
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
    }


    public function test_edit_alumno():void{

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

           
         //Registro correcto con grupos
        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);

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


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>'2024'
        ]);

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA12",
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
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        
        //Actualizar

        $data=[
            'no_control'=>"19161299",
            'curp'=>"AAAAR",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'grupos'=>[
                $grupo->clave_grupo,
                'IA12'
            ]

        ];

        $response = $this->put(route('alumnos.update',"19161299"), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));


        //Validacion de inclusion

        $data=[
            'no_control'=>"19161299",
            'curp'=>"AAAA",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'grupos'=>[
                $grupo->clave_grupo,
                'IA12'
            ]

        ];
        
        $response = $this->put(route('alumnos.update',"19161299"), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

    }

    public function test_delete_alumno():void{

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

       
        $data=[
            'no_control'=>"19161229",
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];


        
        $acceso->assertStatus(302)->assertRedirect(route('home'));

        $response = $this->post(route('alumnos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));


        //Eliminar el alumno
        $alumno=Alumno::find("19161229");
        $this->assertNotNull($alumno);


        $response = $this->delete(route('alumnos.destroy',$alumno->no_control)); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));

        $this->assertDatabaseMissing('alumno', ['no_control' => $alumno->no_control]);
    }

}
