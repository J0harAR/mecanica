<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Herramientas;
use App\Models\Maquinaria;
use App\Models\Insumos;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;


class InventarioTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_invetario(): void
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
        //Ver la tabla de inventario

        $acceso = $this->get(route('inventario.index'))
        ->assertStatus(200)
        ->assertViewIs('inventarios.index');

    }

    public function test_view_herramientas():void{
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

        $acceso =$this->get(route('inventario.index'));
        $acceso->assertStatus(200);

        //aqui vemos el boton para abrir el modal
        $acceso->assertSee('Agregar artículo');

        //Ver si se ve el form de agregar
        $acceso->assertSee('<form',false);

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


        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        
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

        $data_update=[
            'condicion_herramienta' => 'Nueva',
            'estatus'=>'No disponible'
        ];

        $updateCorrecto = $this->put(route('herramientas.update',$herramienta->id_herramientas),$data_update);


        $herramienta_update=Herramientas::where('estatus','No disponible');
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



    public function test_create_maquinaria():void{
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

        $acceso =$this->get(route('inventario.index'));
        $acceso->assertStatus(200);

        //aqui vemos el boton para abrir el modal
        $acceso->assertSee('Agregar artículo');

        //Ver si se ve el form de agregar
        $acceso->assertSee('<form',false);

        $data = [
            'nombre' => 'Inyectora',
            'seccion' => '03',
            'estatus' => 'Disponible',
            'tipo' => 'Maquinaria',
            'cantidad' => 1,
            'tipo_maquina'=>'Maquina de robotica',
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null

        ];
       
        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));

       


        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Inyectora',
            'cantidad' => 1,
            'tipo' => 'Maquina de robotica'
        ]);

             

        $articulo=Catalogo_articulo::where('nombre', 'Inyectora')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Maquinaria'
            ]);
        } 

        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('maquinaria', [
                'id_maquinaria' => '03I01',
               
            ]);
        } 


          //Create pero con la asignacion de los insumos
          $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
           
        ];

        $response_insumo = $this->post(route('inventario.store'), $data_insumos); 

        $maquinaria=Maquinaria::find('03I01');
        
        for ($i = 0; $i < 2; $i++) {
            $this->assertDatabaseHas('insumos', [
                'capacidad' => 3,              
            ]);
        } 

        $insumos=[
                'AI01',
                'AI02',
        ];
        $maquinaria->insumos()->sync($insumos);

        for ($i = 0; $i < 2; $i++) {
            $this->assertDatabaseHas('insumos_maquinaria', [
                'insumo_id' => $insumos[$i],     
                'maquinaria_id' => $maquinaria->id_maquinaria,
                       
            ]);
        } 


    }


    public function test_edit_maquinaria():void{
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
            'nombre' => 'Inyectora',
            'seccion' => '03',
            'estatus' => 'Disponible',
            'tipo' => 'Maquinaria',
            'cantidad' => 1,
            'tipo_maquina'=>'Maquina de robotica',
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null

        ];
       
        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));

       


        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Inyectora',
            'cantidad' => 1,
            'tipo' => 'Maquina de robotica'
        ]);

             

        $articulo=Catalogo_articulo::where('nombre', 'Inyectora')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Maquinaria'
            ]);
        } 

        for ($i = 0; $i < 1; $i++) {
            $this->assertDatabaseHas('maquinaria', [
                'id_maquinaria' => '03I01',
               
            ]);
        }

        $data_update=[
            'estatus'=>'No disponible'
        ];

        $maquinaria=Maquinaria::find('03I01');

        $updateCorrecto = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data_update);
        $updateCorrecto->assertRedirect(route('maquinaria.index'));

        $maquinaria_update=articulo_inventariado::where('id_articulo','03I01')
                                    ->where('estatus','No disponible');
       $this->assertNotNull($maquinaria_update);
       

    }

    public function test_delete_maquinaria():void{

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
            'nombre' => 'Inyectora',
            'seccion' => '03',
            'estatus' => 'Disponible',
            'tipo' => 'Maquinaria',
            'cantidad' => 1,
            'tipo_maquina'=>'Maquina de robotica',
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null

        ];
       
        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));
        

        $maquinaria=Maquinaria::find('03I01');

        $this->assertNotNull($maquinaria);

        $delete_correcto = $this->delete(route('maquinaria.destroy',$maquinaria->id_maquinaria))->assertRedirect(route('maquinaria.index'));
    
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('maquinaria', ['id_maquinaria' => $maquinaria->id_maquinaria]);
    }



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

        //Create pero con la asignacion de los insumos
        $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
           
        ];

        $response_insumo = $this->post(route('inventario.store'), $data_insumos); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('insumos.index'));

        $this->assertDatabaseHas('insumos', [
            'capacidad' => 3,              
        ]);

        $this->assertDatabaseHas('articulo_inventariado', [
            'id_articulo' => 'AI',              
        ]);

        $insumo=Insumos::find('AI01');
        $this->assertNotNull($insumo);
    
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

        $data = [
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,
        ];

        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));

        //Checamos que si la herramienta si se existe en la base de datos
        $this->assertDatabaseHas('catalogo_articulo', [
            'nombre' => 'Aceite Industrial',
            'cantidad' => 2,
            'tipo' => 'Aceite para maquina'
        ]);
        $articulo=Catalogo_articulo::where('nombre', 'Aceite Industrial')->first();
        //No es nulo
        $this->assertNotNull($articulo);

        //Ahora se crean los id de inventariado por la cantidad que se ingreso
        for ($i = 0; $i <2; $i++) {
            $this->assertDatabaseHas('articulo_inventariado', [
                'id_articulo' => $articulo->id_articulo,
                'estatus' => 'Disponible',
                'tipo' => 'Insumos'
            ]);
        }

        $this->assertDatabaseHas('insumos', [
            'capacidad' => 3,    
        ]);
        
        $insumo=Insumos::find('AI01');
        $this->assertNotNull($insumo);

        $data_update=[
            'capacidad' => '20',
            'estatus'=>'No disponible'
        ];

        $updateCorrecto = $this->put(route('insumos.update',$insumo->id_insumo),$data_update);

        $insumo_update=Insumos::where('capacidad',20);
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

        $data = [
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 2,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 3,

        ];
       
        //Creacion correcta de una herramienta
        $response = $this->post(route('inventario.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));
        

        $insumo=Insumos::find('AI01');

        $this->assertNotNull($insumo);

        $delete_correcto = $this->delete(route('insumos.destroy',$insumo->id_insumo))->assertRedirect(route('insumos.index'));
    
        //Signigica que ya no existe en la base de datos
        $this->assertDatabaseMissing('insumos', ['id_insumos' => $insumo->id_insumo]);


    }


}
