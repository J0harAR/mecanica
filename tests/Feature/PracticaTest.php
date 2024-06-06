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
class PracticaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_practicas(): void
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
        

        $response= $this->get(route('practicas.index'))
        ->assertStatus(200)
        ->assertViewIs('practicas.index');

    }



    public function test_create_practicas(): void
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
        
        
        $response= $this->get(route('practicas.create'))
        ->assertStatus(200)
        ->assertViewIs('practicas.crear');



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
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');

        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
        $herramienta=Herramientas::find('HM-T-0234-01');
       
        $this->assertNotNull($herramienta);
        $this->assertNotNull($grupo);
        $this->assertNotNull($docente);
        $this->assertNotNull($asignatura);

        $data=[
            'codigo_practica'=>"001",
            'docente'=>$docente->rfc,
            'clave_grupo'=>$grupo->clave_grupo,
            'nombre_practica'=>"Practica 1",
            'objetivo'=>"Objectivo practica 1",
            'introduccion'=>"Introduccion practica 1",
            'fundamento'=>"Fundamento practica 1",
            'referencias'=>"Referencias practica 1",
            'articulos'=>[
                $herramienta->Articulo_inventariados->Catalogo_articulos->id_articulo
            ],
        ];

        $response = $this->post(route('practicas.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));


        //Ingresar una practica repetida


        $data=[
            'codigo_practica'=>"001",
            'docente'=>$docente->rfc,
            'clave_grupo'=>$grupo->clave_grupo,
            'nombre_practica'=>"Practica 2",
            'objetivo'=>"Objectivo practica 2",
            'introduccion'=>"Introduccion practica 2",
            'fundamento'=>"Fundamento practica 2",
            'referencias'=>"Referencias practica 2",
            'articulos'=>[
                $herramienta->Articulo_inventariados->Catalogo_articulos->id_articulo
            ],
        ];
        $response = $this->post(route('practicas.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.create'));
        $response->assertSessionHas('error');

    }

    public function test_show_practica():void{

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
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');

        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
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
        ]);
        $practica=Practica::find("001");
        $this->assertNotNull($practica);
        $response =$this->get(route('practicas.show',$practica->id_practica));
        $response->assertViewIs('practicas.mostrar');


    }

    public function test_edit_practica():void{
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
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');

        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
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
        ]);
        $practica=Practica::find("001");
        $this->assertNotNull($practica);

        $response =$this->get(route('practicas.edit',$practica->id_practica));
        $response->assertViewIs('practicas.editar');



        $data_update=[
            'codigo_practica'=>"001",
            'docente'=>$docente->rfc,
            'clave_grupo'=>$grupo->clave_grupo,
            'nombre_practica'=>"Practica 2 actualizada",
            'objetivo'=>"Objectivo practica 2",
            'introduccion'=>"Introduccion practica 2",
            'fundamento'=>"Fundamento practica 2",
            'referencias'=>"Referencias practica 2",
            'articulos'=>[
                $herramienta->Articulo_inventariados->Catalogo_articulos->id_articulo
            ],
        ];
        $response =$this->patch(route('practicas.update',$practica->id_practica),$data_update);
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));



        //Cambio de codigo de practica
    
        $data_update=[
            'codigo_practica'=>"002",
            'docente'=>$docente->rfc,
            'clave_grupo'=>$grupo->clave_grupo,
            'nombre_practica'=>"Practica 2 actualizada",
            'objetivo'=>"Objectivo practica 2",
            'introduccion'=>"Introduccion practica 2",
            'fundamento'=>"Fundamento practica 2",
            'referencias'=>"Referencias practica 2",
            'articulos'=>[
                $herramienta->Articulo_inventariados->Catalogo_articulos->id_articulo
            ],
        ];
        $response =$this->patch(route('practicas.update',$practica->id_practica),$data_update);
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));

    }

    public function test_delete_practica():void{
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
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');

        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
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
        ]);

        $practica=Practica::find("001");
        $this->assertNotNull($practica);


        $response=$this->delete(route('practicas.destroy',$practica->id_practica));
        $response->assertRedirect(route('practicas.index'));

        $this->assertDatabaseMissing('practica', ['id_practica' => $practica->id_practica]);
    }


    public function test_filtrar_practicas():void{
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
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find('IA1');

        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
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
        ]);

        $practica=Practica::find("001");

        $data=[
            "docente"=>"Johan",
            "asignatura"=>"Inteligencia artificial",
            "estatus"=>0,
        ];

        $response = $this->post(route('practicas.filtrar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));


        //Caso que el input docente se quede null
        $data=[
            "docente"=>null,
            "asignatura"=>"Inteligencia artificial",
            "estatus"=>0,
        ];
        $response = $this->post(route('practicas.filtrar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));
        
        //Caso que el input de asignatura se quede null
        $data=[
            "docente"=>"Johan",
            "asignatura"=>null,
            "estatus"=>0,
        ];
        $response = $this->post(route('practicas.filtrar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));

        //Caso de que no exista docente
        $data=[
            "docente"=>"Pepe",
            "asignatura"=>null,
            "estatus"=>null,
        ];
        $response = $this->post(route('practicas.filtrar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));
    
        //Caso de que la asignatura no exista
        $data=[
            "docente"=>null,
            "asignatura"=>"Simulacion",
            "estatus"=>null,
        ];
        $response = $this->post(route('practicas.filtrar'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));

    }
   public function test_completar_practica():void{
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
        'periodo'=>'2024'
    ]);

    $grupo=Grupo::find('IA1');

    $data_herramienta=[
        'nombre' => 'Torno',
        'seccion' => 03,
        'estatus' => 'Disponible',
        'tipo' => 'Herramientas',
        'cantidad' => 1,
        'tipo_herramienta' => 'Herramienta manual',
        'dimension_herramienta' => 234,
        'condicion_herramienta' => 'Nueva',
        'tipo_insumo' => null, 
        'capacidad_insumo' => null 


    ];

    $response = $this->post(route('herramientas.store'), $data_herramienta); 
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
    ]);

    $practica=Practica::find("001");


    $response=$this->post(route('practicas.completar',$practica->id_practica));
    $response->assertStatus(302);
    $response->assertRedirect(route('practicas.index'));

    $this->assertDatabaseHas('practica', [
        'id_practica' => $practica->id_practica,
        'estatus' => 1,
      
    ]);

   }

    


   public function test_create_practica_alumno():void{

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
        'periodo'=>'2024'
    ]);

    $grupo=Grupo::find('IA1');

    $data_herramienta=[
        'nombre' => 'Torno',
        'seccion' => 03,
        'estatus' => 'Disponible',
        'tipo' => 'Herramientas',
        'cantidad' => 1,
        'tipo_herramienta' => 'Herramienta manual',
        'dimension_herramienta' => 234,
        'condicion_herramienta' => 'Nueva',
        'tipo_insumo' => null, 
        'capacidad_insumo' => null 


    ];

    $response = $this->post(route('herramientas.store'), $data_herramienta); 
    $herramienta=Herramientas::find('HM-T-0234-01');
   
    $this->assertNotNull($herramienta);
    $this->assertNotNull($grupo);
    $this->assertNotNull($docente);
    $this->assertNotNull($asignatura);
    $data=[
        'codigo_practica'=>"001",
        'docente'=>$docente->rfc,
        'clave_grupo'=>$grupo->clave_grupo,
        'nombre_practica'=>"Practica 1",
        'objetivo'=>"Objectivo practica 1",
        'introduccion'=>"Introduccion practica 1",
        'fundamento'=>"Fundamento practica 1",
        'referencias'=>"Referencias practica 1",
        'articulos'=>[
            $herramienta->Articulo_inventariados->Catalogo_articulos->id_articulo
        ],
    ];

    $response = $this->post(route('practicas.store'), $data); 

    $practica=Practica::find("001");
    $this->assertNotNull($practica);


    Persona::create([
        'curp'=>"BBB",
        'nombre'=>"Johan",
        'apellido_p'=>"Alfaro",
        'apellido_m'=>"Ruiz",
    ]);
    Alumno::create([
        'no_control'=>"19161229",
        'curp'=>"BBB"
    ]);

    $alumno=Alumno::find("19161229");
    $this->assertNotNull($alumno);

    $response =$this->get(route('practicasAlumno.create'));
    $response->assertViewIs('practicas.alumnos');
    //Creacion correcta
    $data =[
        'no_control'=>$alumno->no_control,
        'practica'=>$practica->id_practica,
        'articulos'=>[
            $herramienta->Articulo_inventariados->id_inventario
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicas.index'));
    
    //Caso de que se eliga un articulo que no esta relacionado con la practica
    $data_herramienta=[
        'nombre' => 'Torno R',
        'seccion' => 03,
        'estatus' => 'Disponible',
        'tipo' => 'Herramientas',
        'cantidad' => 1,
        'tipo_herramienta' => 'Herramienta manual',
        'dimension_herramienta' => 234,
        'condicion_herramienta' => 'Nueva',
        'tipo_insumo' => null, 
        'capacidad_insumo' => null 


    ];

    $response = $this->post(route('herramientas.store'), $data_herramienta); 
    $herramienta=Herramientas::find('HM-TR-0234-01');
   
    $this->assertNotNull($herramienta);

    $data =[
        'no_control'=>$alumno->no_control,
        'practica'=>$practica->id_practica,
        'articulos'=>[
            $herramienta->id_herramientas
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicasAlumno.create'));
    $response->assertSessionHas('error');


    //Caso en el que se eligan articulos extra
    $data_herramienta=[
        'nombre' => 'Torno P',
        'seccion' => 03,
        'estatus' => 'Disponible',
        'tipo' => 'Herramientas',
        'cantidad' => 1,
        'tipo_herramienta' => 'Herramienta manual',
        'dimension_herramienta' => 234,
        'condicion_herramienta' => 'Nueva',
        'tipo_insumo' => null, 
        'capacidad_insumo' => null 


    ];

    $response = $this->post(route('herramientas.store'), $data_herramienta); 
    $herramienta_extra=Herramientas::find('HM-TP-0234-01');
    $herramienta=Herramientas::find('HM-T-0234-01');

    $this->assertNotNull($herramienta_extra);

    $data =[
        'no_control'=>$alumno->no_control,
        'practica'=>$practica->id_practica,
        'articulos'=>[
            $herramienta->Articulo_inventariados->id_inventario
        ],
        'articulos-extras'=>[
            $herramienta_extra->Articulo_inventariados->id_inventario
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicas.index'));


    //Caso en el que el alumno no exista

    $herramienta=Herramientas::find('HM-T-0234-01');

    $this->assertNotNull($herramienta_extra);

    $data =[
        'no_control'=>"1917323",
        'practica'=>$practica->id_practica,
        'articulos'=>[
            $herramienta->Articulo_inventariados->id_inventario
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicasAlumno.create'));
    $response->assertSessionHas('alumno_no_encontrado');
   }


}
