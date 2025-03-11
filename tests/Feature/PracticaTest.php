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
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use Spatie\Permission\Models\Permission;
class PracticaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_practicas(): void
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
            Permission::create(['name' => 'ver-practicas']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        

        $response= $this->get(route('practicas.index'))
        ->assertStatus(200)
        ->assertViewIs('practicas.index');

    }



    public function test_create_practicas(): void
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
            Permission::create(['name' => 'crear-practica']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        
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
            'periodo'=>'2024-3'
        ]);

        $grupo=Grupo::find(1);

        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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

        $data=[
            'codigo_practica'=>"001",
            'docente'=>$docente->rfc,
            'grupo'=>$grupo->id,
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
            'grupo'=>$grupo->id,
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
        $response->assertSessionHasErrors([
            'codigo_practica'=>'Codigo de practica duplicado.',
        ]);

        //Ingresar con varios datos nulos ejemplo grupo y articulo
        $data=[
            'codigo_practica'=>"005",
            'docente'=>$docente->rfc,
            'grupo'=>null,
            'nombre_practica'=>"Practica 2",
            'objetivo'=>"Objectivo practica 2",
            'introduccion'=>"Introduccion practica 2",
            'fundamento'=>"Fundamento practica 2",
            'referencias'=>"Referencias practica 2",
            'articulos'=>null
        ];
        $response = $this->post(route('practicas.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'grupo' => 'Grupo obligatorio.',
            'articulos' => 'Articulos obligatorios.',
        ]);


    }

    public function test_show_practica():void{

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
            Permission::create(['name' => 'ver-practica']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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

        $grupo=Grupo::find(1);

        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
            'clave_grupo'=>$grupo->id,
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
            Permission::create(['name' => 'editar-practica']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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

        $grupo=Grupo::find(1);
        
        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
            'clave_grupo'=>$grupo->id,
            'nombre'=>"Practica 1",
            'objetivo'=>"Objectivo practica 1",
            'introduccion'=>"Introduccion practica 1",
            'fundamento'=>"fundamento practica 1",
            'referencias'=>"referencias practica 1",
            'estatus'=>0,
        ]);
        
        $practica=Practica::find("001");
        $practica->catalogo_articulos()->sync(["HM-T-0234"]);
        $this->assertNotNull($practica);
        $response =$this->get(route('practicas.edit',$practica->id_practica));
        $response->assertViewIs('practicas.editar');

        //Actualizacion correcta de una practica

        $data_update = [
            'docente' => $docente->rfc,
            'grupo' => $grupo->id,
            'nombre_practica' => "Practica 2 actualizada",
            'objetivo' => "Objetivo practica 2",
            'introduccion' => "Introduccion practica 2",
            'fundamento' => "Fundamento practica 2",
            'referencias' => "Referencias practica 2",
            'articulos' => [
                "HM-T-0234"
            ],
        ];

        $response =$this->patch(route('practicas.update',$practica->id_practica),$data_update);
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));


   
        //Validacion si no se le asignan articulos
        $data_update=[
            'codigo_practica'=>"001",
            'docente'=>$docente->rfc,
            'grupo'=>$grupo->id,
            'nombre_practica'=>"Practica 2 actualizada",
            'objetivo'=>"Objectivo practica 2",
            'introduccion'=>"Introduccion practica 2",
            'fundamento'=>"Fundamento practica 2",
            'referencias'=>"Referencias practica 2",
            'articulos'=>null
        ];
        $response =$this->patch(route('practicas.update',$practica->id_practica),$data_update);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'articulos' => 'Articulos obligatorios.',
        ]);

        //Validacion si se deja algun campo en blanco/ o todos
        $data_update=[
            'codigo_practica'=>null,
            'docente'=>null,
            'grupo'=>null,
            'nombre_practica'=>null,
            'objetivo'=>null,
            'introduccion'=>null,
            'fundamento'=>null,
            'referencias'=>null,
            'articulos'=>null
        ];
        $response =$this->patch(route('practicas.update',$practica->id_practica),$data_update);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'docente' => 'Docente obligatorio.',
            'grupo' => 'Grupo obligatorio.',
            'nombre_practica' => 'Nombre de la practica obligatorio.',
            'objetivo' => 'Objectivo obligatorio.',
            'introduccion' => 'Introducción obligatoria.',
            'fundamento' => 'Fundamento obligatorio.',
            'referencias' => 'Referencias obligatorias.',
            'articulos' => 'Articulos obligatorios.',
        ]);


    }

    public function test_delete_practica():void{
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
            Permission::create(['name' => 'borrar-practica']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }


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

        $grupo=Grupo::find(1);
        
        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
            'clave_grupo'=>$grupo->id,
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
            Permission::create(['name' => 'ver-practicas']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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

        Asignatura::create([
            'clave'=>'IAAAAA',
            'nombre'=>'Inteligencia artificial2'
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>'2024'
        ]);

        $grupo=Grupo::find(1);

        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
            'clave_grupo'=>$grupo->id,
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

        $data = [
            
            'asignatura' => 'IAAAAA',
        ];
        $response = $this->post(route('practicas.filtrar'), $data);
    
        $response->assertStatus(302);
        $response->assertRedirect(route('practicas.index'));    

    }
   public function test_completar_practica():void{
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
        Permission::create(['name' => 'completar-practica']),
    ];

    $user->syncPermissions($permissions);

    foreach ($permissions as $permission) {
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }

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

    $grupo=Grupo::find(1);

    Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
        'clave_grupo'=>$grupo->id,
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
        Permission::create(['name' => 'crear-practica-alumno']),
    ];

    $user->syncPermissions($permissions);

    foreach ($permissions as $permission) {
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }

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

    Grupo::create([
        'id_docente'=>$docente->rfc,
        'clave_grupo'=>"ISA",
        'clave_asignatura'=>$asignatura->clave,
        'periodo'=>'2024-3'
    ]);

    $grupo=Grupo::find(1);

    Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
  

    $practica=Practica::create([
        'id_practica'=>"001",
        'id_docente'=>$docente->rfc,
        'clave_grupo'=>$grupo->id,
        'nombre'=>"Practica 1",
        'objetivo'=>"Objectivo practica 1",
        'introduccion'=>"Introduccion practica 1",
        'fundamento'=>"fundamento practica 1",
        'referencias'=>"referencias practica 1",
        'estatus'=>0,
    ]);

  

    $practica=Practica::find("001");
    $this->assertNotNull($practica);

    $practica->catalogo_articulos()->sync(["HM-T-0234"]);

    Persona::create([
        'curp'=>"BBB",
        'nombre'=>"Johan",
        'apellido_p'=>"Alfaro",
        'apellido_m'=>"Ruiz",
    ]);
    Alumno::create([
        'no_control'=>"19161229",
        'curp'=>"BBB",
    ]);

    $alumno=Alumno::find("19161229");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([$grupo->id]);
    
    $response =$this->get(route('practicasAlumno.create'));
    $response->assertViewIs('practicas.alumnos');
    
    //Creacion correcta de una practica alumno
    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-T-0234-01"
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicas.alumnos.index'));
   
    //Validacion si un alumno no pertence al grupo de la practica

    Persona::create([
        'curp'=>"RR",
        'nombre'=>"Johan",
        'apellido_p'=>"Alfaro",
        'apellido_m'=>"Ruiz",
    ]);
    Alumno::create([
        'no_control'=>"19161230",
        'curp'=>"RR",
    ]);

    $alumno=Alumno::find("19161230");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([2]);

    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-T-0234-01"
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



  //Validacion ningun alumno seleccionado o articulo
    $alumno=Alumno::find("19161229");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([$grupo->id]);
 

    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>null,
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertSessionHasErrors([
        'articulos' => 'Articulos obligatorios.',
      
    ]);


    //Validacion de articulos no esten asociados a la practica.
    Catalogo_articulo::create([
        'id_articulo'=>"HM-TT-0234",
        'nombre' => 'Torno T',
        'cantidad'=>1,
        'seccion'=>null,
        'tipo'=>"Herramientas",

    ]);

    Articulo_inventariado::create([
        'id_inventario'=>"HM-TT-0234-01",
        'id_articulo'=>"HM-TT-0234",
        'estatus'=>"Disponible",
        'tipo'=>"Herramientas",
    ]);

    Herramientas::create([
        'id_herramientas'=>"HM-TT-0234-01",
        'condicion'=>"Buen estado",
        'dimension'=>234,
    ]);

    
    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-TT-0234-01"
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


    //Validacion ningun alumno seleccionado
    $alumno=Alumno::find("19161229");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([$grupo->id]);
 
    
    $data =[
        'alumnos'=>null,
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-T-0234-01"
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicasAlumno.create'));
    $response->assertSessionHasErrors([
        'alumnos' => 'Alumnos obligatorios.',
    ]);

    //Validacion de practica sin grupo asignado

    $alumno=Alumno::find("19161229");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([$grupo->id]);
    
    $practica=Practica::create([
        'id_practica'=>"0013",
        'id_docente'=>$docente->rfc,
        'clave_grupo'=>null,
        'nombre'=>"Practica 1",
        'objetivo'=>"Objectivo practica 1",
        'introduccion'=>"Introduccion practica 1",
        'fundamento'=>"fundamento practica 1",
        'referencias'=>"referencias practica 1",
        'estatus'=>0,
    ]);

    $practica=Practica::find("0013");
    $this->assertNotNull($practica);
    
    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-T-0234-01"
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
    $practica=Practica::find("001");

    $data =[
        'alumnos'=>[$alumno->no_control],
        'practica'=>$practica->id_practica,
        'articulos'=>[
           "HM-T-0234-01"
        ],
        'articulos-extras'=>[
             "HM-TT-0234-01"
        ],
        'fecha'=>"2024-07-02",
        'no_equipo'=>2,
        'hora_entrada'=>"21:31:00",
        'hora_salida'=>"21:32:00"
    ];
    $response = $this->post(route('practicasAlumno.store'), $data);
    $response->assertStatus(302); 
    $response->assertRedirect(route('practicas.alumnos.index'));


   }

   public function test_view_practicas_alumnos():void{
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
        Permission::create(['name' => 'ver-practicas']),
    ];

    $user->syncPermissions($permissions);

    foreach ($permissions as $permission) {
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }
    

    $response= $this->get(route('practicas.alumnos.index'))
    ->assertStatus(200)
    ->assertViewIs('practicas.practicas_alumnos');
   }

   public function test_obtener_alumnos_practica():void{
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
        Permission::create(['name' => 'ver-practicas']),
    ];

    $user->syncPermissions($permissions);

    foreach ($permissions as $permission) {
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }
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
        'clave_grupo'=>"ISA",
        'clave_asignatura'=>$asignatura->clave,
        'periodo'=>'2024-3'
    ]);

    Persona::create([
        'curp'=>"RR",
        'nombre'=>"Johan",
        'apellido_p'=>"Alfaro",
        'apellido_m'=>"Ruiz",
    ]);
    Alumno::create([
        'no_control'=>"19161230",
        'curp'=>"RR",
    ]);

    $alumno=Alumno::find("19161230");
  
    $this->assertNotNull($alumno);
    $alumno->grupos()->sync([1]);


    $grupo=Grupo::find(1);

    Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>1,
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
  

    $practica=Practica::create([
        'id_practica'=>"001",
        'id_docente'=>$docente->rfc,
        'clave_grupo'=>$grupo->id,
        'nombre'=>"Practica 1",
        'objetivo'=>"Objectivo practica 1",
        'introduccion'=>"Introduccion practica 1",
        'fundamento'=>"fundamento practica 1",
        'referencias'=>"referencias practica 1",
        'estatus'=>0,
    ]);

  

    $practica=Practica::find("001");
    $this->assertNotNull($practica);

    $practica->catalogo_articulos()->sync(["HM-T-0234"]);

    $practica->alumnos()->sync([$alumno->no_control => [
        'fecha' => "2024-07-02",
        'no_equipo' => 2,
        'hora_entrada' => "21:31:00",
        'hora_salida' => "21:32:00"
    ]]);
  

    $data=[
        "id"=>"001"
    ];
 
    $response = $this->get(route('practicas.alumno.obtener', $data))
    ->assertStatus(200);

   }

  
}
