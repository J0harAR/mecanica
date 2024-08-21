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


            //Creacion correcta de una maquinaria
            Catalogo_articulo::create([
                'id_articulo'=>"03DI",
                'nombre' => 'durometro ibertest',
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

            $data_update = [
                'estatus'=>'No disponible',
                
                'insumos' => [
                    'AI01'=>200, 
                    'AI02'=>200,                
                ],

                'insumos-cantidad-actual' => [
                     'AI01'=>250, 
                     'AI02'=>20, 
                ],
                'insumos-cantidad-minima' => [
                    'AI01'=> 5, 
                    'AI02'=>15, 
                ],
            ];
        
            $response = $this->post(route('herramientas.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('herramientas.index'));
    
            $herramienta=Herramientas::where('id_herramientas', 'HM-T-0234-01')->first();
            
            //No es nulo
            $this->assertNotNull($herramienta);
    
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
           
          
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
    
           

          
    
    
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
    
            $response_insumo = $this->post(route('insumos.store'), $data_insumos); 
    
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

        //data con los insumos como parametro  cuando ya existe registro
        $data_con_insumos = [
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
            'capacidad_insumo' => null,
            'insumos'=>[
                'AI01'
            ]

        ];
        $response = $this->post(route('maquinaria.store'), $data_con_insumos); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));

     //data con los insumos como parametro  cuando no existe ninguna maquinaria
        $data_con_insumos = [
            'nombre' => 'Inyectora Prueba',
            'seccion' => '03',
            'estatus' => 'Disponible',
            'tipo' => 'Maquinaria',
            'cantidad' => 1,
            'tipo_maquina'=>'Maquina de robotica',
            'tipo_herramienta' => 'Herramienta manual',
            'dimension_herramienta' => 15,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => null, 
            'capacidad_insumo' => null,
            'insumos'=>[
                'AI01'
            ]

        ];
        $response = $this->post(route('maquinaria.store'), $data_con_insumos); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));



     //Insertar una maquina existente
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
        $response = $this->post(route('maquinaria.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));

        $maquinaria=Maquinaria::find('03I02');
        $this->assertNotNull($maquinaria);
    
    
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
           
      
            $response = $this->post(route('maquinaria.store'), $data); 
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
    
            $response_insumo = $this->post(route('insumos.store'), $data_insumos); 
    
            
            $maquinaria=Maquinaria::find('03I01');

            $insumos=[
                'AI01',
                'AI02',
            ];
           
            $maquinaria->insumos()->sync($insumos);

            $data_update = [
                'estatus'=>'No disponible',
                
                'insumos' => [
                    'AI01'=>200, 
                    'AI02'=>200,                
                ],

                'insumos-cantidad-actual' => [
                     'AI01'=>100, 
                     'AI02'=>20, 
                ],
                'insumos-cantidad-minima' => [
                    'AI01'=> 5, 
                    'AI02'=>15, 
                ],
            ];

            $updateCorrecto = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data_update);
            $updateCorrecto->assertRedirect(route('maquinaria.index'));
    
            $maquinaria_update=articulo_inventariado::where('id_articulo','03I01')
                                        ->where('estatus','No disponible');
           $this->assertNotNull($maquinaria_update);


            //Caso de la cantidad actual del insumo es mayor a la capacidad total
            $data_update = [
                'estatus'=>'No disponible',
                
                'insumos' => [
                    'AI01'=>200, 
                    'AI02'=>200,                
                ],

                'insumos-cantidad-actual' => [
                     'AI01'=>250, 
                     'AI02'=>20, 
                ],
                'insumos-cantidad-minima' => [
                    'AI01'=> 5, 
                    'AI02'=>15, 
                ],
            ];

            $update = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data_update);
            $update->assertRedirect(route('maquinaria.index'));
            $update->assertSessionHas('error');

            //Caso de la cantidad minima del insumo es mayor a la capacidad total
            $data_update = [
                'estatus'=>'No disponible',
                
                'insumos' => [
                    'AI01'=>200, 
                    'AI02'=>200,                
                ],

                'insumos-cantidad-actual' => [
                     'AI01'=>20, 
                     'AI02'=>20, 
                ],
                'insumos-cantidad-minima' => [
                    'AI01'=> 500, 
                    'AI02'=>15, 
                ],
            ];
            $update = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data_update);
            $update->assertRedirect(route('maquinaria.index'));
            $update->assertSessionHas('error');

            //Caso cuando no esta definida la capacidad de un insumo
            $data_update = [
                'estatus'=>'No disponible',
                
                'insumos' => [
                    'AI01'=>null, 
                    'AI02'=>null,                
                ],

                'insumos-cantidad-actual' => [
                     'AI01'=>20, 
                     'AI02'=>20, 
                ],
                'insumos-cantidad-minima' => [
                    'AI01'=> 500, 
                    'AI02'=>15, 
                ],
            ];
            $update = $this->put(route('maquinaria.update',$maquinaria->id_maquinaria),$data_update);
            $update->assertRedirect(route('maquinaria.index'));
            $update->assertSessionHas('error');
    
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
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            
    
            $maquinaria=Maquinaria::find('03I01');
    
            $this->assertNotNull($maquinaria);
    
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
           
            //Creacion correcta de una maquinaria
            $response = $this->post(route('maquinaria.store'), $data); 
            $response->assertStatus(302);
            $response->assertRedirect(route('maquinaria.index'));
            
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
    
            $response_insumo = $this->post(route('insumos.store'), $data_insumos); 

            //data de los insumos de los inputs

            $data=[
                'insumos' => [
                    'AI01' ,
                    'AI02'               
                ]
            ];

            $maquinaria=Maquinaria::find('03I01');
            $asignacion = $this->patch(route('maquinaria.insumos_asignar',$maquinaria->id_maquinaria),$data);
            $asignacion->assertStatus(302);
            $asignacion->assertRedirect(route('maquinaria.index'));
            


        }

    
}
