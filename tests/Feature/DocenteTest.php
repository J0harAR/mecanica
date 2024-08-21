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
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use App\Models\Herramientas;

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
       
        //Registro correcto del docente
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


        //Caso en el que se cree un docente pero con curp de un alumno(inclusion)
        Persona::create([
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);
        Alumno::create([
            'no_control'=>"19161229",
            'curp'=>"AAA"
        ]);

        $data=[
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'rfc'=>"RRR",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];

        $response = $this->post(route('docentes.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.create'));
        $response->assertSessionHas('error');
        
        
        
        //se crea un docente con un mismo rfc
        $data=[
            'curp'=>"CURP3",        
            'rfc'=>"BBB",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];

        $response = $this->post(route('docentes.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.create'));
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

    public function test_edit_docente():void{
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
        $file = UploadedFile::fake()->image('foto.jpg');
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
            'foto'=>$file,
            'telefono'=>"839213"
        ]);

        $docente=Docente::find("DDD");
        $this->assertNotNull($docente);
        
        $data=[
            'nombre'=>"pepe",
            'rfc'=>"pepe",
            'curp'=>"DDD",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'area'=>"Sistemas",
            'foto'=>$file,
            'telefono'=>"839213"
        ];  

        $response =$this->put(route('docentes.update',$docente->rfc),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));

        //Validacion de iunclusion curp le pertenece a un alumno
        Persona::create([
            'curp'=>"PPP",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);
        Persona::create([
            'curp'=>"CURPPPPP",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Alumno::create([
            'no_control'=>"19161229",
            'curp'=>"PPP"
        ]);

        Docente::create([
            'rfc'=>"DDDRR",
            'curp'=>"CURPPPPP",
            'area'=>"Sistemas",
            'foto'=>$file,
            'telefono'=>"839213"
        ]);
        $docente=Docente::find("DDDRR");

        $data=[
            'curp'=>"PPP",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'rfc'=>"BBB",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];

        $response =$this->put(route('docentes.update',$docente->rfc),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        $response->assertSessionHas('error');

        //Validacion de curp repetida
        Persona::create([
            'curp'=>"CURP1",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        $data=[
            'curp'=>"CURP1",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
            'rfc'=>"BBB",
            'area'=>"Sistemas",
            'telefono'=>"1234",
            'foto'=>  $file,
        ];

        $response =$this->put(route('docentes.update',$docente->rfc),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        $response->assertSessionHas('error');


    }

    public function test_delete_docente():void{
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
        $file = UploadedFile::fake()->image('foto.jpg');
        Persona::create([
            'curp'=>"CURPPPPP",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDDRR",
            'curp'=>"CURPPPPP",
            'area'=>"Sistemas",
            'foto'=>$file,
            'telefono'=>"839213"
        ]);

        $docente=Docente::find('DDDRR');

        $response =$this->delete(route('docentes.destroy',$docente->rfc));
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));



        //Eliminar un docente que tiene registrada una practica
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


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>'2024-3'
        ]);

        $grupo=Grupo::find('IA1');

        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>0,
            'seccion'=>null,
            'tipo'=>"Herramientas",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"HM-T-0234-01",
            'id_articulo'=>"HM-T-0234",
            'estatus'=>"Disponible",
            'tipo'=>"Herramientas",
        ]);

        Herramientas::create([
            'id_herramientas'=>"HM-T-0234-01",
            'condicion'=>"Buen estado",
            'dimension'=>234,
        ]);

        $herramienta=Herramientas::find('HM-T-0234-01');
       
        $this->assertNotNull($herramienta);
        $this->assertNotNull($grupo);
        $this->assertNotNull($docente);
        $this->assertNotNull($asignatura);


        Practica::create([
            'id_practica'=>"001",
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>$grupo->clave_grupo,
            'nombre'=>"Practica 1",
            'objetivo'=>"Objectivo practica 1",
            'introduccion'=>"Introduccion practica 1",
            'fundamento'=>"fundamento practica 1",
            'referencias'=>"referencias practica 1",
            'estatus'=>0,
            'catalogo_articulos'=>[
                'HM-T-0234'
            ]
        ]);
        $response =$this->delete(route('docentes.destroy',$docente->rfc));
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.index'));
        $response->assertSessionHas('error');



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
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        $periodo=Periodo::find("2024-3");
        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');
        //Filtro de asignar
        $data=[
            "asignatura"=>$asignatura->clave,
            "docente"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_asignaturas'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.asigna'));
        //caso de input null
        $data=[
            "asignatura"=>null,
            "docente"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_asignaturas'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.asigna'));
        
        
        //Filtro de desasignar
        $data=[
            "id_asignatura"=>$asignatura->clave,
            "rfc"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_grupos'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.eliminar_asignacion'));


        //Caso en el que se deja en blanco algun input en desasignar
        $data=[
            "id_asignatura"=>null,
            "rfc"=>$docente->rfc,
            "periodo"=>$periodo->clave
        ];
        $response = $this->post(route('docentes.filtrar_grupos'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.eliminar_asignacion'));
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
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);
        

        $periodo=Periodo::find("2024-3");
        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>null,
            'clave_grupo'=>"A1",
            'clave_asignatura'=>$asignatura->clave,
            'clave_periodo'=>$periodo->clave,
        ]);
        $grupo=Grupo::find("A1");

        $data=[
            "clave_periodo"=>"2024-3",
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
            "clave_periodo"=>"2024-3",
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

        //Caso en el que no se selecciona ningun grupo

        $data=[
            "clave_periodo"=>"2024-3",
            "grupos" => [],
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
            'clave'=>"2024-3"
        ]);

        

        $periodo=Periodo::find("2024-3");
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
            "periodo"=>$periodo->clave,
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

        //Caso que  no se seleccione ningun grupo
        $data=[
            "periodo"=>$periodo->clave,
            "grupos" => [],
            "rfc"=>$docente->rfc,
        ];
        $response = $this->post(route('docentes.eliminar_asignacion'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('docentes.eliminacion_asignacion'));
    }


    public function test_obtener_datos_docente():void{

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

        Persona::create([
            'curp'=>"OOAZ900824MTSRLL08",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Docente::create([
            'rfc'=>"DDD",
            'curp'=>"OOAZ900824MTSRLL08",
            'area'=>"Sistemas",
            'foto'=>"sdsada",
            'telefono'=>"839213"
        ]);

            $data=[
                "id"=>"DDD"
            ];
         
          $response= $this->get(route('docentes.grupos'),$data)
            ->assertStatus(200);

    }

}
