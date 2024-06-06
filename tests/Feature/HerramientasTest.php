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

        $data = [
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 10,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 
        ];

        //tipo nulo
        $datos_nulos=[
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 10,
            'tipo_herramienta' => null,
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 

        ];

        //Tipo de herramienta nulo 
        $response = $this->post(route('herramientas.store'), $datos_nulos); 
        $response->assertRedirect(route('herramientas.index'));
        $response->assertStatus(302);
        $response->assertSessionHas('tipo_vacia', 'Seleccione el  tipo de herramienta');



        //Creacion correcta de una herramienta
        $response = $this->post(route('herramientas.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Torno',
            'cantidad' => 10,
            'tipo' => 'Herramienta manual'
        ]);
        $herramienta=Catalogo_articulo::where('nombre', 'Torno')->first();
        //No es nulo
        $this->assertNotNull($herramienta);

        //Ahora se crean los id de inventariado por la cantidad que se ingreso
        for ($i = 0; $i < 10; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $herramienta->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Herramientas'
            ]);
        }

        $this->assertDatabaseHas('herramientas', [
            'condicion' => 'Nueva',
            'dimension' => 15
        ]);

         //Insertar una herramienta ya registrada

         $data = [
            'nombre' => 'Torno',
            'seccion' => 03,
            'estatus' => 'Disponible',
            'tipo' => 'Herramientas',
            'cantidad' => 1,
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null 
        ];


        $response = $this->post(route('herramientas.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        $herramienta=Herramientas::find('HM-T-0015-11');
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

        $data = [
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

        //Creacion correcta de una herramienta
        $response = $this->post(route('herramientas.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Torno',
            'cantidad' => 1,
            'tipo' => 'Herramienta manual'
        ]);
        $articulo=Catalogo_articulo::where('nombre', 'Torno')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        //Ahora se crean los id de inventariado por la cantidad que se ingreso
        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Herramientas'
            ]);
        }

        $this->assertDatabaseHas('herramientas', [
            'condicion' => 'Nueva',
            'dimension' => 234
        ]);
        
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

        $data_update=[
            'condicion_herramienta' => 'Nueva',
            'estatus'=>'No disponible'
        ];
        //Update sin cambiar la condicion de la herramienta
        $updateCorrecto = $this->put(route('herramientas.update',$herramienta->id_herramientas),$data_update);
        $herramienta_update=Herramientas::where('estatus','No disponible');
        $this->assertNotNull($herramienta_update);
        $updateCorrecto->assertRedirect(route('herramientas.index'));
    

        //Update cambiando la condicion de la herramienta
        $data_update=[
            'condicion_herramienta' => 'Desgastada',
            'estatus'=>'No disponible'
        ];
        $updateCorrecto = $this->put(route('herramientas.update',$herramienta->id_herramientas),$data_update);
        $herramienta_update=Herramientas::where('condicion','Desgastada');
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

        $data = [
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

        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Torno',
            'cantidad' => 1,
            'tipo' => 'Herramienta manual'
        ]);
        $articulo=Catalogo_articulo::where('nombre', 'Torno')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        //Ahora se crean los id de inventariado por la cantidad que se ingreso
        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Herramientas'
            ]);
        }

        $this->assertDatabaseHas('herramientas', [
            'condicion' => 'Nueva',
            'dimension' => 234
        ]);
        
        $herramienta=Herramientas::find('HM-T-0234-01');
        $this->assertNotNull($herramienta);

        $delete_correcto = $this->delete(route('herramientas.destroy',$herramienta->id_herramientas))->assertRedirect(route('herramientas.index'));
       
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('herramientas', ['id_herramientas' => $herramienta->id_herramienta]);

    }
}
