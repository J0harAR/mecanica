<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Lectura;
use Illuminate\Support\Facades\Artisan;

use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Maquinaria;
use App\Models\Insumos;
class LecturaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_lecturas(): void
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

        $response= $this->get(route('lector.index'))
        ->assertStatus(200)
        ->assertViewIs('lector_niveles.index');


    }

    public function test_create_lectura():void{

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

        //Creacion correcta de la lectura

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
            "maquina"=>"03MI01",
            "observaciones"=>"Sin observaciones",
            "fecha"=>"2024/07/02",
            "insumos"=>[
                "AI"=>15
            ]
        ];

        $response_insumo = $this->post(route('lector.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('lector.index'));

        //Validacion cuando se excede de la capacidad

        $data=[
            "maquina"=>"03MI01",
            "observaciones"=>"Sin observaciones",
            "fecha"=>"2024/07/02",
            "insumos"=>[
                "AI"=>200
            ]
        ];

        $response_insumo = $this->post(route('lector.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('lector.index'));

         //Validacion cuando no se pone observacion
         $data=[
            "maquina"=>"03MI01",
            "observaciones"=>null,
            "fecha"=>"2024/07/02",
            "insumos"=>[
                "AI"=>15
            ]
        ];

        $response_insumo = $this->post(route('lector.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('lector.index'));
        $response_insumo->assertSessionHas('error');

         //Validacion cuando no se selecciona la maquina
         $data=[
            "maquina"=>null,
            "observaciones"=>"Sin observaciones",
            "fecha"=>"2024/07/02",
            "insumos"=>[
                "AI"=>15
            ]
        ];

        $response_insumo = $this->post(route('lector.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertRedirect(route('lector.index'));
        $response_insumo->assertSessionHas('error');

    }


    public function test_comportamiento_insumos():void{
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

        //Creacion correcta de la lectura

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
            "maquinaria_id"=>"03MI01",
            "fecha_inicio"=>"2024/07/02",
            "fecha_fin"=>"2024/07/06" ,
        
        ];
     
        $response = $this->get(route('comportamiento.insumos', $data))
        ->assertStatus(200);
    }
}
