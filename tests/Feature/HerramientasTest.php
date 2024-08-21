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
use App\Models\Herramientas;
class HerramientasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_herramientas(): void
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

        $acceso = $this->get(route('herramientas.index'))
        ->assertStatus(200)
        ->assertViewIs('herramientas.index');
    }


    public function test_create_herramienta():void{
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

        $acceso =$this->get(route('herramientas.index'));
        $acceso->assertStatus(200);

    


     //Creacion correcta de una herramienta

        Catalogo_articulo::create([
            'id_articulo'=>"HM-T-0234",
            'nombre' => 'Torno',
            'cantidad'=>0,
            'seccion'=>null,
            'tipo'=>"Herramientas",

        ]);

        //tipo nulo
        $data=[
            "id_articulo"=>"HM-T-0234",
            'estatus' => 'Disponible',
            'cantidad' => 1,
            'condicion_herramienta' => 'Buen estado',
        ];
    
        $response = $this->post(route('herramientas.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        $herramienta=Herramientas::where('id_herramientas', 'HM-T-0234-01')->first();
        
        //No es nulo
        $this->assertNotNull($herramienta);

    }

    public function test_edit_herramienta():void{

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


        $data_update=[
            'condicion_herramienta' => 'Buen estado',
            'estatus'=>'No disponible'
        ];
        //Update sin cambiar la condicion de la herramienta
        $updateCorrecto = $this->put(route('herramientas.update',"HM-T-0234-01"),$data_update);
        $herramienta_update=Articulo_inventariado::where('estatus','No disponible')->first();
        $this->assertNotNull($herramienta_update);
        $updateCorrecto->assertRedirect(route('herramientas.index'));
    

        //Update cambiando la condicion de la herramienta
        $data_update=[
            'condicion_herramienta' => 'Desgastada',
            'estatus'=>'No disponible'
        ];
        $updateCorrecto = $this->put(route('herramientas.update',"HM-T-0234-01"),$data_update);
        $herramienta_update=Articulo_inventariado::where('condicion','Desgastada');
        $this->assertNotNull($herramienta_update);
        $updateCorrecto->assertRedirect(route('herramientas.index'));


    }
    public function test_delete_herramienta():void{

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

        $delete_correcto = $this->delete(route('herramientas.destroy',"HM-T-0234-01"))->assertRedirect(route('herramientas.index'));
       
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('herramientas', ['id_herramientas' => "HM-T-0234-01"]);


        //Caso en el que sea mayor a 0 la cantidad en catalogo articulo cuando se elimine debe disminuir

    }
}
