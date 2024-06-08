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
use App\Models\Herramientas;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Asignatura;
use App\Models\Persona;
use App\Models\Alumno;
use App\Models\Periodo;

class PrestamoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_prestamos(): void
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
        

        $response= $this->get(route('prestamos.index'))
        ->assertStatus(200)
        ->assertViewIs('prestamos.index');
        //Cracion de un docente y una herramienta

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
        //Retornar los prestamos

        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));
        
        //Acceder a la vista con prestamos registrados
        $response= $this->get(route('prestamos.index'))
        ->assertStatus(200)
        ->assertViewIs('prestamos.index');


      
    }

    public function test_create_prestamo():void{
        
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
        
        
        //Creacion de un prestamo correcto 
        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 2,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];  

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

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
        //Retornar los prestamos

        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));


        //Creacion de prestamo sin docente no encontrado
        $data=[
            "rfc"=>"asdadasd",
            "herramienta"=>"HM-T-0234-02",
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));
        $response->assertSessionHas('docente_no_encontrado');


        //Creacion de prestamo con una herramienta ya ocupada
        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];
        
        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));
        $response->assertSessionHas('herramienta_no_disponible');
        

        //Creacion de prestamo con algun dato null en los inputs
        $data=[
            "rfc"=>null,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];
        
        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));
        $response->assertSessionHas('error');


    
    }

    public function test_edit_prestamo():void {
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
        
        
        //Creacion de un prestamo correcto 
        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 2,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];  

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

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
        //Retornar los prestamos

        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));


        //Cambiar fecha de devolucion del prestamo
        $data=[
            "rfc"=>$docente->rfc,
            "fecha_devolucion"=>"2024-07-03",
        ];


        $response = $this->patch(route('prestamos.update',1), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));

        
    }
    public function test_delete_prestamo():void {
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
        
        
        //Creacion de un prestamo correcto 
        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 2,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];  

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

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
        //Retornar los prestamos

        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));


        //Cambiar fecha de devolucion del prestamo
        $response = $this->delete(route('prestamos.destroy',1), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));

        $this->assertDatabaseMissing('prestamo', ['id' => 1]);

    }


    public function test_finalizar_prestamo():void{
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
        
        
        //Creacion de un prestamo correcto 
        $data_herramienta=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 2,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 234,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 


        ];  

        $response = $this->post(route('herramientas.store'), $data_herramienta); 
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

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
        //Retornar los prestamos

        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));



        $response = $this->patch(route('prestamos.finalizar',1)); 
        $response->assertStatus(302);
        $response->assertRedirect(route('prestamos.index'));

        $this->assertDatabaseHas('prestamo', [
            'id' => 1,
            'estatus' =>"Finalizado"
        ]);

    }

    
}
