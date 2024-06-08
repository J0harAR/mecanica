<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
use App\Models\Periodo;

class DocenteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_docentes(): void
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
        

        $response= $this->get(route('docentes.index'))
        ->assertStatus(200)
        ->assertViewIs('docentes.index');
    }


    public function test_create_docente():void{
        Artisan::call('migrate');
        Storage::fake('local');

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
        

        $response= $this->get(route('docentes.create'))
        ->assertStatus(200)
        ->assertViewIs('docentes.create');
        
        $file = UploadedFile::fake()->image('foto.jpg');
       
        $data=[
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'rfc'=>"BBB",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];
        $response = $this->post(route('docentes.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
       
        $docente=Docente::find("BBB");
        $this->assertNotNull($docente);

        //Revisar que en caso de que se cree un docente repetido

        $response = $this->post(route('docentes.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        $response->assertSessionHas('error');


        //Caso en el que se cree un docente pero con curp de un alumno(inclusion)
        Persona::create([
            'curp'=>"CCC",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);
        Alumno::create([
            'no_control'=>"19161229",
            'curp'=>"CCC"
        ]);

        $data=[
            'curp'=>"CCC",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'rfc'=>"BBB",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];

        $response = $this->post(route('docentes.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        $response->assertSessionHas('error');
    }



    public function test_show_docente():void{

        Artisan::call('migrate');
        Storage::fake('local');

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
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);
    
        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"AAA",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

        $docente=Docente::find("DDD");
        $this->assertNotNull($docente);

        $response =$this->get(route('docentes.show',$docente->rfc));
        $response->assertViewIs('docentes.show');
    }


    public function test_view_asigar():void{
        Artisan::call('migrate');
        Storage::fake('local');

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
        

        $response= $this->get(route('docentes.asigna'))
        ->assertStatus(200)
        ->assertViewIs('docentes.asignar');


    }

    public function test_view_filtro():void {
       
        Artisan::call('migrate');
        Storage::fake('local');

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
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"AAA",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        Periodo::create([
            'clave'=>"2024"
        ]);

        $periodo=Periodo::find("2024");
        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        $data=[
            "asignatura"=>$asignatura->clave,
            "docente"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_asignaturas'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.asigna'));

        //Caso en el que se deja en blanco algun input
        $data=[
            "asignatura"=>null,
            "docente"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_asignaturas'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.asigna'));
        $response->assertSessionHas('error');

    }

    public function test_asignar_grupo():void {
        Artisan::call('migrate');
        Storage::fake('local');

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
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"AAA",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        Periodo::create([
            'clave'=>"2024"
        ]);

        

        $periodo=Periodo::find("2024");
        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>null,
            'clave_grupo'=>"A1",
            'clave_asignatura'=>$asignatura->clave,
            'clave_periodo'=>null,
        ]);
        $grupo=Grupo::find("A1");

        $data=[
            "clave_periodo"=>$periodo->clave,
            "grupos" => [
                $grupo->clave_grupo => [
                'asignatura' => $grupo->clave_asignatura
             ]
            ],
            "rfc_docente"=>$docente->rfc,
        ];

        $response = $this->post(route('docentes.asignar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        
        //Caso que el grupo ya cuente con algun docente asignado
        $data=[
            "clave_periodo"=>$periodo->clave,
            "grupos" => [
                $grupo->clave_grupo => [
                'asignatura' => $grupo->clave_asignatura
             ]
            ],
            "rfc_docente"=>$docente->rfc,
        ];

        $response = $this->post(route('docentes.asignar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.asigna'));
        $response->assertSessionHas('error');
        
    }

    public function test_eliminar_asignacion():void{

    Artisan::call('migrate');
        Storage::fake('local');

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
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"AAA",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        Periodo::create([
            'clave'=>"2024"
        ]);

        

        $periodo=Periodo::find("2024");
        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"A1",
            'clave_asignatura'=>$asignatura->clave,
            'clave_periodo'=>$periodo->clave,
        ]);
        $grupo=Grupo::find("A1");


        $response= $this->get(route('docentes.eliminacion_asignacion'))
        ->assertStatus(200)
        ->assertViewIs('docentes.desasignar');

        //Se filtrar los grupos que cuenta el docente para eliminar la asignacion 

        $data=[
            "rfc"=>$docente->rfc,
            "id_asignatura"=>$asignatura->clave,
            "periodo"=>$periodo->clave,
        
        ];

        $response = $this->post(route('docentes.filtrar_grupos'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.eliminacion_asignacion'));


        //Eliminar la asignacion de forma correcta
        $data=[
            "periodoc"=>$periodo->clave,
            "grupos" => [
                $grupo->clave_grupo => [
                'asignatura' => $grupo->clave_asignatura
             ]
            ],
            "rfc"=>$docente->rfc,
        ];
        $response = $this->post(route('docentes.eliminar_asignacion'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        
    }

}
