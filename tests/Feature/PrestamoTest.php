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
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use Spatie\Permission\Models\Permission;
class PrestamoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_prestamos(): void
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
            Permission::create(['name' => 'ver-prestamos']),
            Permission::create(['name' => 'crear-prestamo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        

        $response= $this->get(route('prestamos.index'))
        ->assertStatus(200)
        ->assertViewIs('prestamos.index');
        //Creacion de un docente y una herramienta

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
            Permission::create(['name' => 'ver-prestamos']),
            Permission::create(['name' => 'crear-prestamo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        
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


        //Prestamo sin rfc del docente ni fecha de devolucion
        $data=[
            "rfc"=>null,
            "herramienta"=>$herramienta->id_herramientas,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>null,
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'rfc' => 'Debe seleccionar un docente.',
            'fecha_devolucion' => 'Debe seleccionar una fecha de devolución.',
        ]);

        //Prestamo sin herramienta 
        $data=[
            "rfc"=>$docente->rfc,
            "herramienta"=>null,
            "fecha_prestamo"=>"2024-07-02",
            "fecha_devolucion"=>"2024-07-03",
        ];

        $response = $this->post(route('prestamos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'herramienta' => 'Debe seleccionar una herramienta.',   
        ]);
    }

    public function test_edit_prestamo():void {
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
            Permission::create(['name' =>'ver-prestamos']),
            Permission::create(['name' =>'crear-prestamo']),
            Permission::create(['name' => 'editar-prestamo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        
        //Creacion de un prestamo correcto 
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

        //Caso que no se ponga fecha de devolucion ni docente
        $data=[
            "rfc"=>null,
            "fecha_devolucion"=>null,
        ];


        $response = $this->patch(route('prestamos.update',1), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'rfc' => 'Debe seleccionar un docente.',
            'fecha_devolucion' => 'Debe seleccionar una fecha de devolución.',
        ]);

        
    }
 

    public function test_finalizar_prestamo():void{
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
            Permission::create(['name' =>'ver-prestamos']),
            Permission::create(['name' =>'crear-prestamo']),
            Permission::create(['name' => 'finalizar-prestamo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        
        
        //Creacion de un prestamo correcto 
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
