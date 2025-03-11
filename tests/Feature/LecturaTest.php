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
use Spatie\Permission\Models\Permission;
class LecturaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_lecturas(): void
    {
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
            Permission::create(['name' => 'ver-lecturas']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

        $response= $this->get(route('lector.index'))
        ->assertStatus(200)
        ->assertViewIs('lector_niveles.index');


    }

    public function test_create_lectura():void{

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
            Permission::create(['name' => 'crear-lectura']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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
        $response_insumo->assertSessionHasErrors([
            'observaciones' => 'La observación es obligatoria',
        ]);

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
        $response_insumo->assertSessionHasErrors([
            'maquina' => 'Seleccione una maquinaria.',
        ]);

       //Validacion cuando no se selecciona la fecha
       $data=[
            "maquina"=>"03MI01",
            "observaciones"=>"Sin observaciones",
            "fecha"=>null,
            "insumos"=>[
                "AI"=>15
            ]
        ];

        $response_insumo = $this->post(route('lector.store'), $data); 
        $response_insumo->assertStatus(302);
        $response_insumo->assertSessionHasErrors([
            'fecha' => 'La fecha es obligatoria.',
        ]);

    }


    public function test_comportamiento_insumos():void{
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
            Permission::create(['name' => 'ver-lecturas']),
            Permission::create(['name' => 'crear-lectura']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

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
