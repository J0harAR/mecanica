<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Periodo;
class ReportesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_reportes_prestamo(): void
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
        $response= $this->get(route('reporte.prestamo'))
        ->assertStatus(200);


    }

    public function test_view_reportes_inventario(): void
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


        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        $data=[
            "periodo"=>"2024-3"

        ];
       
        $response= $this->post(route('reporte.inventario'),$data)
        ->assertStatus(200);

        //Caso en el que no exista periodo
        $data=[
            "periodo"=>"2024"

        ];
       
        $response= $this->post(route('reporte.inventario'),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('error');

    }

    public function test_view_reportes_herramientas(): void
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

        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        $data=[
            "periodo"=>"2024-3"

        ];
       
        
        $response= $this->post(route('reporte.herramientas'),$data)
        ->assertStatus(200);

        //Caso en el que no exista periodo
        $data=[
            "periodo"=>"2024"

        ];
       
        $response= $this->post(route('reporte.herramientas'),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('herramientas.index'));
        $response->assertSessionHas('error');


    }

    public function test_view_reportes_insumos(): void
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

        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        $data=[
            "periodo"=>"2024-3"

        ];
       
        
        $response= $this->post(route('reporte.insumos'),$data)
        ->assertStatus(200);

        //Caso en el que no exista periodo
        $data=[
            "periodo"=>"2024"

        ];
       
        $response= $this->post(route('reporte.insumos'),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('insumos.index'));
        $response->assertSessionHas('error');


    }
    public function test_view_reportes_maquinaria(): void
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

        Periodo::create([
            'clave'=>'2024-3',
            'fecha_inicio'=>"2024-08-01",
            'fecha_final'=>"2024-12-20",
        ]);

        $data=[
            "periodo"=>"2024-3"

        ];
       
        
        $response= $this->post(route('reporte.maquinarias'),$data)
        ->assertStatus(200);

        //Caso en el que no exista periodo
        $data=[
            "periodo"=>"2024"

        ];
       
        $response= $this->post(route('reporte.maquinarias'),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('maquinaria.index'));
        $response->assertSessionHas('error');


    }

    public function test_view_reportes_practicas(): void
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


        
        $response= $this->post(route('reporte.practicas'))
        ->assertStatus(200);


    }
}
