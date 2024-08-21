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
class InsumosTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_insumos():void{
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
        //Ver la tabla de inventario

        $acceso = $this->get(route('insumos.index'))
        ->assertStatus(200)
        ->assertViewIs('insumos.index');

    }



    public function test_create_insumo():void{
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

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>0,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        //Create pero con la asignacion de los insumos
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

      ;

 
    }

    public function test_edit_insumo(){

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
    }

    public function test_delete_insumo():void{
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
