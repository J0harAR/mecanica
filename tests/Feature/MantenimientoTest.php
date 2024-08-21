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
use App\Models\Maquinaria;
use App\Models\Insumos;
use App\Models\Mantenimiento;

class MantenimientoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_mantenimiento(): void
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

        $acceso = $this->get(route('mantenimiento.index'))
        ->assertStatus(200)
        ->assertViewIs('mantenimiento.index');
    }


    public function test_create_mantenimiento(): void
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

        
        //Registro de mantenimiento correcto
        Catalogo_articulo::create([
            'id_articulo'=>"03MI",
            'nombre' =>'maquina inyectora',
            'cantidad'=>1,
            'seccion'=>"03",
            'tipo'=>"Maquinaria",

        ]);

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"03MI01",
            'id_articulo'=>"03MI",
            'estatus'=>"Disponible",
            'tipo'=>"Maquinaria",
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

        Maquinaria::create([
            'id_maquinaria'=>"03MI01",
        ]);

        $maquinaria=Maquinaria::find('03MI01');
      


        $maquinaria->insumos()->attach("AI", ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);
      
        $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    "AI01" => 10,       
                ],

        ];
       
        $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
        $response->assertStatus(302);
        $response->assertRedirect(route('mantenimiento.index'));


        //Caso que la cantidad de insumo sobre pase la capacidad es decir algun derrame
        $data_mantenimiento = [
            'maquina'=>$maquinaria->id_maquinaria,
            'fecha'=>'2024-07-02',
            
            'insumos' => [
                "AI01" => 160,            
            ],

        ];
        $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
        $response->assertStatus(302);
        $response->assertRedirect(route('mantenimiento.index'));
        $response->assertSessionHas('error');
        

        //Caso en el que la cantidad que se esta ingresando supera del que se tiene en stock
        $data_mantenimiento = [
            'maquina'=>$maquinaria->id_maquinaria,
            'fecha'=>'2024-07-02',
            
            'insumos' => [
                 "AI01" => 250,   

            ],

        ];
        $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
        $response->assertStatus(302);
        $response->assertRedirect(route('mantenimiento.index'));
        $response->assertSessionHas('errores_cantidad');

         //Caso de que algunos campos esten vacios

         $data_mantenimiento = [
            'maquina'=>null,
            'fecha'=>'2024-07-02',
            
            'insumos' => [
                "AI01" => 160,       
            ],

        ];
        $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
        $response->assertStatus(302);
        $response->assertRedirect(route('mantenimiento.index'));
        $response->assertSessionHas('error');
           
    }




    public function test_obtener_datos_maquinaria():void{


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


        
        //Registro de mantenimiento correcto
        Catalogo_articulo::create([
            'id_articulo'=>"03MI",
            'nombre' =>'maquina inyectora',
            'cantidad'=>1,
            'seccion'=>"03",
            'tipo'=>"Maquinaria",

        ]);

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"03MI01",
            'id_articulo'=>"03MI",
            'estatus'=>"Disponible",
            'tipo'=>"Maquinaria",
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

        Maquinaria::create([
            'id_maquinaria'=>"03MI01",
        ]);

        $maquinaria=Maquinaria::find('03MI01');
      


        $maquinaria->insumos()->attach("AI", ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);

        $response = $this->get(route('get-datos-maquinaria', ['id' => '03MI01']));

        $response->assertStatus(200);

    }



    public function test_insumos_por_maquinaria():void{
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


        
        //Registro de mantenimiento correcto
        Catalogo_articulo::create([
            'id_articulo'=>"03MI",
            'nombre' =>'maquina inyectora',
            'cantidad'=>1,
            'seccion'=>"03",
            'tipo'=>"Maquinaria",

        ]);

        Catalogo_articulo::create([
            'id_articulo'=>"AI",
            'nombre' => 'Aceite Industrial',
            'cantidad'=>1,
            'seccion'=>null,
            'tipo'=>"Insumos",

        ]);

        Articulo_inventariado::create([
            'id_inventario'=>"03MI01",
            'id_articulo'=>"03MI",
            'estatus'=>"Disponible",
            'tipo'=>"Maquinaria",
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

        Maquinaria::create([
            'id_maquinaria'=>"03MI01",
        ]);

        $maquinaria=Maquinaria::find('03MI01');
      


        $maquinaria->insumos()->attach("AI", ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);

        $data=[
            "id"=>"03MI01"
        ];

        $response = $this->get(route('get-insumos-por-maquinaria', ['id' => '03MI01']));
        $response->assertStatus(200);
    }


}
