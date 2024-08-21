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

class MaquinariaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   
     public function test_view_maquinaria(): void
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
 
         $acceso = $this->get(route('maquinaria.index'))
         ->assertStatus(200)
         ->assertViewIs('maquinaria.index');
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


            //Creacion correcta de una maquinaria sin insumos
            Catalogo_articulo::create([
                'id_articulo'=>"03DI",
                'nombre' => 'durometro ibertest',
                'cantidad'=>0,
                'seccion'=>"03",
                'tipo'=>"Maquinaria",
    
            ]);

             $data=[
                "id_articulo"=>"03DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
            ];

            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
    
            $maquinaria=Maquinaria::where('id_maquinaria', '03DI01')->first();
            
            //No es nulo
            $this->assertNotNull($maquinaria);

            //Validaciones de la capacidad , capacidad actual y capacidad minima de cada insumo que se le asigne

            //La cantidad actual no puede ser menor que la cantidad mínima
            Catalogo_articulo::create([
                'id_articulo'=>"02DI",
                'nombre' => 'durometro ibertest',
                'cantidad'=>0,
                'seccion'=>"02",
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
                'id_inventario'=>"AI01",
                'id_articulo'=>"AI",
                'estatus'=>"Disponible",
                'tipo'=>"Insumos",
            ]);
    
            Insumos::create([
                'id_insumo'=>"AI01",
                'capacidad'=>200,
            ]);

             $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>200, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>1,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> 5, 
                    
                ],
            ];

            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');
            //La cantidad mínima no puede ser mayor que la capacidad
    
            $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>200, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>1,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> 270, 
                    
                ],
            ];
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');


            //La cantidad actual no puede ser mayor que la capacidad 

            $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>200, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>1000,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> 10, 
                    
                ],
            ];
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');




            //Cantidad mínima no definida para el insumo con ID

            
            $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>200, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>6,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> null, 
                    
                ],
            ];
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');

            //Capacidad no definida para el insumo

            
            $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>null, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>5,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> 1, 
                    
                ],
            ];
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');

            //Creacion correcta de una maquinaria con  insumos

            $data=[
                "id_articulo"=>"02DI",
                'estatus' => 'Disponible',
                'cantidad' => 1,
                
                'insumos' => [
                    'AI'=>10, 
                       
                ],

                'insumos-cantidad-actual' => [
                     'AI'=>5,                
                ],
                'insumos-cantidad-minima' => [
                    'AI'=> 1, 
                    
                ],
            ];
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
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
           
            //La cantidad mínima no puede ser mayor que la capacidad para el insumo
            $data=[
                "id_articulo"=>"03MI01",
                'estatus' => 'No disponible',
       
                'insumos' => [
                    'AI'=>10, 
                       
                ],

                'insumos-cantidad-minima' => [
                    'AI'=>50, 
                    
                ],
            ];

           
            $response = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data);
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');


            //Capacidad no definida para el insumo
            $data=[
                "id_articulo"=>"03MI01",
                'estatus' => 'No disponible',
       
                'insumos' => [
                    'AI'=>null, 
                       
                ],

                'insumos-cantidad-minima' => [
                    'AI'=> 1, 
                    
                ],
            ];

           
            $response = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data);
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            $response->assertSessionHas('error');





            //Correcto actualizacion de maquinaria
            $data=[
                "id_articulo"=>"03MI01",
                'estatus' => 'No disponible',
       
                'insumos' => [
                    'AI'=>200, 
                       
                ],

                'insumos-cantidad-minima' => [
                    'AI'=> 1, 
                    
                ],
            ];

           
            $response = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data);
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
          
    
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

            Catalogo_articulo::create([
                'id_articulo'=>"03MI",
                'nombre' =>'maquina inyectora',
                'cantidad'=>1,
                'seccion'=>"03",
                'tipo'=>"Maquinaria",
    
            ]);
          
            Articulo_inventariado::create([
                'id_inventario'=>"03MI01",
                'id_articulo'=>"03MI",
                'estatus'=>"Disponible",
                'tipo'=>"Maquinaria",
            ]);

    
            Maquinaria::create([
                'id_maquinaria'=>"03MI01",
            ]);
            $maquinaria=Maquinaria::find("03MI01");
           
            $delete_correcto = $this->delete(route('maquinaria.destroy',$maquinaria->id_maquinaria))->assertRedirect(route('maquinaria.index'));
        
            //Signigica que ya no existe en la base de datos
            $this->assertDatabaseMissing('maquinaria', ['id_maquinaria' => $maquinaria->id_maquinaria]);
        }

        public function test_asignar_insumos():void{
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
                'id_articulo'=>"03MI",
                'nombre' =>'maquina inyectora',
                'cantidad'=>1,
                'seccion'=>"03",
                'tipo'=>"Maquinaria",
    
            ]);
          
            Articulo_inventariado::create([
                'id_inventario'=>"03MI01",
                'id_articulo'=>"03MI",
                'estatus'=>"Disponible",
                'tipo'=>"Maquinaria",
            ]);

    
            Maquinaria::create([
                'id_maquinaria'=>"03MI01",
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

            $data=[
                'insumos'=>[
                    "AI"
                ]
            ];
    
        
          

            $maquinaria=Maquinaria::find('03MI01');
            //ASignacion correcta
            $asignacion = $this->patch(route('maquinaria.insumos_asignar',$maquinaria->id_maquinaria),$data);
            $asignacion->assertStatus(302);
            $asignacion->assertRedirect(route('maquinaria.index'));
            
            //Validacion si no se selecciona ninguno
              $data=[
                'insumos'=>null
            ];
            
            $asignacion = $this->patch(route('maquinaria.insumos_asignar',$maquinaria->id_maquinaria),$data);
            $asignacion->assertStatus(302);
            $asignacion->assertRedirect(route('maquinaria.index'));
            $asignacion->assertSessionHas('error');
            
          
            
        }

        public function test_desasignar_insumos():void{

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
          
    
            //Desasignar correctamente
            $maquinaria->insumos()->attach("AI", ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);
            $data=[
                "insumos"=>[
                    "AI"
                ]
            ];
            $response = $this->patch(route('maquinaria.insumos_desasignar',$maquinaria->id_maquinaria),$data);
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            //Si no se selecciono ningun insumo


            $data=[
                'insumos'=>null
            ];
            
            $response = $this->patch(route('maquinaria.insumos_desasignar',$maquinaria->id_maquinaria),$data);
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
        
            
        }

    
}
