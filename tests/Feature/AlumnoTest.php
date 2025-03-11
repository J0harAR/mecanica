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
use Spatie\Permission\Models\Permission;
class AlumnoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_alumnos(): void
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

        $permission = Permission::create(['name' => 'ver-alumnos']);

         // Asignar el permiso al usuario
         $user->givePermissionTo($permission);

         $this->assertTrue($user->hasPermissionTo('ver-alumnos'));

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
            'clave'=>'2025-1',
            'fecha_inicio'=>"2025-01-01",
            'fecha_final'=>"2025-07-20",
        ]);


        $docente=Docente::find("DDD");
        $asignatura=Asignatura::find('IA');
        $periodo=Periodo::find('2025-1');

        Grupo::create([
            'id_docente'=>$docente->rfc,
            'clave_grupo'=>"IA1",
            'clave_asignatura'=>$asignatura->clave,
            'periodo'=>$periodo->clave
        ]);

        $grupo=Grupo::find(1);
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
            Permission::create(['name' => 'ver-alumnos']),
            Permission::create(['name' => 'crear-alumnos']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        $acceso->assertStatus(302)->assertRedirect(route('home'));
       //Caso correcto de registro del alumno
        $data=[
            'no_control'=>"19161229",
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];

        $response = $this->post(route('alumnos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));

         //Validacion de CURP duplicada
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
        $response->assertSessionHasErrors([
            'curp' => 'Curp duplicado.',
        ]);


        //Caso en el que se registre con un mismo numero de control
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
            'curp'=>"AAAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];

        $response = $this->post(route('alumnos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'curp' => 'Curp duplicado.',
            'no_control' => 'Este número de control ya está registrado.',
        ]);
 
    }


    public function test_edit_alumno():void{
        Storage::fake('local');

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
            Permission::create(['name' => 'ver-alumnos']),
            Permission::create(['name' => 'editar-alumnos']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        $acceso->assertStatus(302)->assertRedirect(route('home'));

        //Actualizacion correcta del alumno
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


        //Validacion de curp repetida
        Persona::create([
            'curp'=>"CURP1",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        $data=[
            'no_control'=>"19161299",
            'curp'=>"CURP1",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
       
        ];

        $response = $this->put(route('alumnos.update',"19161299"), $data); 
        $response->assertStatus(302);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'curp' => 'Curp duplicado.',
        ]);

        //Validacion con numero de control repetido
        Persona::create([
            'curp'=>"CURP2",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);
        Persona::create([
            'curp'=>"CURP",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ]);

        Alumno::create([
            'no_control'=>"19161212",
            'curp'=>"CURP1",
          ]);

        Alumno::create([
            'no_control'=>"19161213",
            'curp'=>"CURP2",
          ]);

          $data=[
            'no_control'=>"19161213",
            'curp'=>"CURP5",
            'nombre'=>"Johannnn",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
       
          ];

        $response = $this->put(route('alumnos.update',"19161212"), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'no_control' => 'Este número de control ya está registrado.',
        ]);

    }

    public function test_delete_alumno():void{

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
            Permission::create(['name' => 'ver-alumnos']),
            Permission::create(['name' => 'crear-alumnos']),
            Permission::create(['name' => 'borrar-alumnos']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        $acceso->assertStatus(302)->assertRedirect(route('home'));

       
        $data=[
            'no_control'=>"19161229",
            'curp'=>"AAA",
            'nombre'=>"Johan",
            'apellido_p'=>"Alfaro",
            'apellido_m'=>"Ruiz",
        ];


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
            Permission::create(['name' => 'ver-alumnos']),
            Permission::create(['name' => 'asigar-grupo-alumno']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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
            'grupo'=>1
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));

        //Validacion cuando no se selecciona un alumno

        $data=[
            'selected_alumnos'=>null,
            'grupo'=>1
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'selected_alumnos' => 'No se seleccionó ningún alumno.',
        ]);

        //Validacion si no se encuentra un grupo

        $data=[
            'selected_alumnos'=>"19161299",
            'grupo'=>32
        ];

        $response = $this->post(route('alumnos.asignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'grupo' => 'Grupo no encontrado',
        ]);

    }




    public function test_desasignar_grupo_alumno():void{
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
            Permission::create(['name' => 'ver-alumnos']),
            Permission::create(['name' => 'desasigar-grupo-alumno']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }


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
                1
            ]
          ]);

       
          //Validacion si no selecciona ningun alumno
        $data = [
            'selected_alumnos' => [],  
            'clave_grupo' => 1,
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'selected_alumnos' => 'No se seleccionó ningún alumno.',
        ]);


        //Validacion si no se encuentra un grupo
        $data=[
            'selected_alumnos'=>["19161299"],
            'clave_grupo'=>32
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'clave_grupo' => 'Grupo no encontrado',
        ]);

        //Se desasigna correctamente

        $data=[
            'selected_alumnos'=>["19161299"],
            'clave_grupo'=>1
        ];

        $response = $this->post(route('alumnos.desasignar-grupo'),$data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('alumnos.index'));
    }

    public function test_filtrar_alumnos_grupo():void{
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
            Permission::create(['name' => 'ver-alumnos']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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
            'grupo'=>1,
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
            Permission::create(['name' => 'crear-alumnos']),
        ];

        $user->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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
