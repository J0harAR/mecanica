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

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            "email" => '19161221@itoaxaca.edu.mx',
            "password" => Hash::make('Johanar2-'),
        ]);
        
        
        $acceso = $this->post(route('login'), [
            'email' => '19161221@itoaxaca.edu.mx',
            'password' => 'Johanar2-',
        
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

        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');
        $periodo=Periodo::find('2024-3');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>$periodo->clave
        ]);

        $grupo=Grupo::find('IA1');
        $this->assertNotNull($grupo);
       
        $data=[
            'no_control'=>"19161299",
            'curp'=>"AAAAR",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];

        $response = $this->post(route('alumnos.store'), $data); 

        $response= $this->get(route('alumnos.index'))
        ->assertStatus(200)
        ->assertViewIs('alumnos.index');

        //Caso en el que no hay periodo registrado

        $periodo->delete();

        $response= $this->get(route('alumnos.index'))
        ->assertStatus(200)
        ->assertViewIs('blank');

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

 
    }


    public function test_edit_alumno():void{
        Storage::fake('local');

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
            'curp'=>"OOAZ900824MTSRLL08",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Alumno::create([
          'no_control'=>"19161299",
          'curp'=>"OOAZ900824MTSRLL08",
        
        ]);

        $data=[
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL02",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];

        
        $response = $this->put(route('alumnos.update',"19161299"), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));


        //Validacion de CURP registrada en un docente

        Persona::create([
            'curp'=>"OOAZ900824MTSRLL01",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        $file = UploadedFile::fake()->image('foto.jpg');
        
        Docente::create([
            'rfc'=>"OOAZ900824MTS",
            'curp'=>"OOAZ900824MTSRLL01",
            'area'=>"Sistemas",
            'foto'=>$file,
            'telefono'=>"951-450-2945",
        ]);

        $data=[
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL01",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
       
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


    public function test_asignar_grupo_alumno():void{
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

        Alumno::create([
          'no_control'=>"19161299",
          'curp'=>"OOAZ900824MTSRLL08",
        
        ]);

        //Asignacion de grupo correcta 
        Periodo::create([
            
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);

        
        Grupo::create([
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>"IA",
            'clave_periodo'=>'2024-3'
        ]);


        $data=[
            'selected_alumnos'=>"19161299",
            'grupo'=>"IA1"
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));

        //Validacion cuando no se selecciona un alumno

        $data=[
            'selected_alumnos'=>null,
            'grupo'=>"IA1"
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        //Validacion si no se encuentra un grupo

        $data=[
            'selected_alumnos'=>"19161299",
            'grupo'=>"IA12"
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');
    }




    public function test_desasignar_grupo_alumno():void{
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

       
        //Asignacion de grupo correcta 
        Periodo::create([
            
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);

        
        Grupo::create([
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>"IA",
            'clave_periodo'=>'2024-3'
        ]);

        Alumno::create([
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL08",
            'grupos'=>[
                'IA1'
            ]
          ]);

       
          //Validacion si no selecciona ningun alumno
        $data = [
            'selected_alumnos' => [],  
            'clave_grupo' => "IA1",
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        //Validacion si no se encuentra un grupo
        $data=[
            'selected_alumnos'=>["19161299"],
            'clave_grupo'=>"IA12"
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
        $response->assertSessionHas('error');

        //Se desasigna correctamente

        $data=[
            'selected_alumnos'=>["19161299"],
            'clave_grupo'=>"IA1"
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
    }

    public function test_filtrar_alumnos_grupo():void{
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

       
        Periodo::create([
            
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);

        
        Grupo::create([
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>"IA",
            'clave_periodo'=>"2024-3"
        ]);

        Alumno::create([
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL08",
            'grupos'=>[
                'IA1'
            ]
          ]);

          $data=[
            'grupo'=>"IA1",
            'periodo'=>"2024-3"
          ];
          //Filtrar correctamente
          $response = $this->post(route('alumnos.filtrar-grupos'),$data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('alumnos.index'));
          //Filtrar con datos nulos
          $data=[
            'grupo'=>null,
            'periodo'=>null
          ];

          $response = $this->post(route('alumnos.filtrar-grupos'),$data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('alumnos.index'));
          //Filtar cuando no se encuentra ningun grupo
          
          $data=[
            'grupo'=>null,
            'periodo'=>"2024-3"
          ];

          $response = $this->post(route('alumnos.filtrar-grupos'),$data); 
          $response->assertStatus(302);
          $response->assertRedirect(route('alumnos.index'));


    }


    public function test_check_no_control():void{
        
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

        Alumno::create([
            'no_control'=>"19161299",
            'curp'=>"OOAZ900824MTSRLL08",
          ]);

          //Aqui me regresa si se encuentra ya registrado un alumno pero en el mismo input no retorna a ninguna view
          $response= $this->get(route('alumnos.check_no_control',"19161299"))
            ->assertStatus(200);
       
    }

}
