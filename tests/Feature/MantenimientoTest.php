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

        $acceso =$this->get(route('inventario.index'));
        $acceso->assertStatus(200);

        $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 1000,
           
        ];
        $data_insumos2=[
            'nombre' => 'Aceite',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 100,
        ];

        

        $response_insumo = $this->post(route('insumos.store'), $data_insumos); 
        $response_insumo = $this->post(route('insumos.store'), $data_insumos2); 
        
        $insumo=Insumos::find('AI01');
        $insumo2=Insumos::find('A01');

        $this->assertNotNull($insumo);
        $this->assertNotNull($insumo2);

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
            'capacidad_insumo' => null,
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


        
        $maquinaria=Maquinaria::find('03I01');
        
        for ($i = 0; $i < 3; $i++) {
            $this->assertDatabaseHas('insumos', [
                'capacidad' => 1000,              
            ]);
        } 

        $maquinaria->insumos()->attach($insumo->id_insumo, ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);
        $maquinaria->insumos()->attach($insumo2->id_insumo, ['capacidad' => 1000, 'cantidad_actual' => 100,'cantidad_minima'=>10]);
      
            $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    $insumo->id_insumo => 10,       
                ],

            ];

            //Registro de mantenimiento correcto
            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
            $response->assertStatus(302);
            $response->assertRedirect(route('mantenimiento.index'));
            $response->assertSessionHas('success', 'El registro del mantenimiento ha sido creado exitosamente,');
      

             //Caso de seleccionar un insumo que no le pertenece a la maquinaria
             $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    'AI03'=>10, 

                ],

            ];

            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
            $response->assertStatus(302);
            $response->assertRedirect(route('mantenimiento.index'));
            $response->assertSessionHas('error');
        
            //Caso que la cantidad de insumo sobre pase la capacidad es decir algun derrame

            $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    $insumo->id_insumo => 160,       
                ],

            ];
            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
            $response->assertStatus(302);
            $response->assertRedirect(route('mantenimiento.index'));
            $response->assertSessionHas('error');

            //Caso de que algunos campos esten vacios

            $data_mantenimiento = [
                'maquina'=>null,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    $insumo->id_insumo => 160,       
                ],

            ];
            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
            $response->assertStatus(302);
            $response->assertRedirect(route('mantenimiento.index'));
            $response->assertSessionHas('error');

            //Caso que no haya suficiente insumo en inventario para el mantenimiento
            $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    $insumo2->id_insumo => 160,   

                ],

            ];
            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
            $response->assertStatus(302);
            $response->assertRedirect(route('mantenimiento.index'));
            $response->assertSessionHas('errores_cantidad');

           
    }


    public function test_delete_mantenimiento():void{

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

        $data_insumos=[
            'nombre' => 'Aceite Industrial',
            'seccion' => null,
            'estatus' => 'Disponible',
            'tipo' => 'Insumos',
            'cantidad' => 1,
            'tipo_maquina'=>'',
            'tipo_herramienta' => '',
            'dimension_herramienta' => null,
            'condicion_herramienta' => 'Nueva',
            'tipo_insumo' => 'Aceite para maquina', 
            'capacidad_insumo' => 1000,
           
        ];
     

        $response_insumo = $this->post(route('insumos.store'), $data_insumos); 


        $insumo=Insumos::find('AI01');
    
        $this->assertNotNull($insumo);

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
            'capacidad_insumo' => null,
        ];
       
      
        $response = $this->post(route('maquinaria.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));

    
        
        $maquinaria=Maquinaria::find('03I01');
        

        $maquinaria->insumos()->attach($insumo->id_insumo, ['capacidad' => 100, 'cantidad_actual' => 50,'cantidad_minima'=>10]);
      
            $data_mantenimiento = [
                'maquina'=>$maquinaria->id_maquinaria,
                'fecha'=>'2024-07-02',
                
                'insumos' => [
                    $insumo->id_insumo => 10,       
                ],

            ];

            //Registro de mantenimiento correcto
            $response=$this->post(route('mantenimiento.store'),$data_mantenimiento);
      
            $response=$this->delete(route('mantenimiento.destroy',1));
            $response->assertRedirect(route('mantenimiento.index'));




    }


}
