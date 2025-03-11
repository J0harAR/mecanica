<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Insumos;

use Spatie\Permission\Models\Permission;
class InsumosTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_insumos():void{
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
            Permission::create(['name' => 'ver-insumos']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        //Ver la tabla de inventario

        $acceso = $this->get(route('insumos.index'))
        ->assertStatus(200)
        ->assertViewIs('insumos.index');

    }



    public function test_create_insumo():void{
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
            Permission::create(['name' => 'ver-insumos']),
            Permission::create(['name' => 'crear-insumo']),
           
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>0,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        $data=[
            'id_articulo'=>'AI',
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'capacidad_insumo' => 100, 
        ];

        $response_insumo = $this->post(route('insumos.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('insumos.index'));
       
        $insumo=Insumos::where('id_insumo', 'AI01')->first();
        
        //No es nulo
        $this->assertNotNull($insumo);

        //Caso en el que no se pone capacidad de insumo
        $data=[
            'id_articulo'=>'AI',
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'capacidad_insumo' => null, 
        ];

        $response = $this->post(route('insumos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'capacidad_insumo' => 'La capacidad es obligatoria.',
        ]);
        //Caso en el que no se pone cantidad
 
        $data=[
            'id_articulo'=>'AI',
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => null,
            'capacidad_insumo' => 10, 
        ];

        $response = $this->post(route('insumos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'cantidad' => 'Cantidad obligatoria.',
        ]);


    }

    public function test_edit_insumo(){

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
            Permission::create(['name' => 'ver-insumos']),
            Permission::create(['name' => 'editar-insumo']),
           
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"AI01",
            'id_articulo'=>"AI",
            'estatus'=>"Disponible",
            'tipo'=>"Insumos",
        ]);

        Insumos::create([
            'id_insumo'=>"AI01",
            'capacidad'=>200,
        ]);


        $data_update=[
            'capacidad'=>200,
            'estatus'=>'No disponible'
        ];
        //Update sin cambiar la capacidad del insumo
        $updateCorrecto = $this->put(route('insumos.update',"AI01"),$data_update);

        $insumo=Articulo_inventariado::where('estatus','No disponible')->first();
        $this->assertNotNull($insumo);

        $updateCorrecto->assertRedirect(route('insumos.index'));
    
        //Update cambiando la capacidad del insumo
        $data_update=[
            'capacidad'=>200,
            'estatus'=>'Disponible'
        ];
        $updateCorrecto = $this->put(route('insumos.update',"AI01"),$data_update);
        $insumo_update=Insumos::where('capacidad',200);
        $this->assertNotNull($insumo_update);
        $updateCorrecto->assertRedirect(route('insumos.index'));


        //Caso en el que la capacidad es null
        $data_update=[
            'capacidad'=>null,
            'estatus'=>'Disponible'
        ];
        $response = $this->put(route('insumos.update',"AI01"),$data_update);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'capacidad' => 'La capacidad es obligatoria.',
        ]);

        //Caso en el que estatus es null

        $data_update=[
            'capacidad'=>14,
            'estatus'=>null
        ];
        $response = $this->put(route('insumos.update',"AI01"),$data_update);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'estatus' => 'Estatus del insumo obligatorio.',
        ]);

    }

    public function test_delete_insumo():void{
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
            Permission::create(['name' => 'ver-insumos']),
            Permission::create(['name' => 'borrar-insumo']),
           
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
      

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"AI01",
            'id_articulo'=>"AI",
            'estatus'=>"Disponible",
            'tipo'=>"Insumos",
        ]);

        Insumos::create([
            'id_insumo'=>"AI01",
            'capacidad'=>200,
        ]);



        $delete_correcto = $this->delete(route('insumos.destroy',"AI01"))->assertRedirect(route('insumos.index'));
    
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('insumos', ['id_insumos' =>"AI01"]);


    }
}
