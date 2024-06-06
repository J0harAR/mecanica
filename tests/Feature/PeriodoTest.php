<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;

use App\Models\Periodo;

class PeriodoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_periodos(): void
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
        

        $response= $this->get(route('periodos.index'))
        ->assertStatus(200)
        ->assertViewIs('periodos.index');

        
    }


    public function test_create_periodo(){
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
        
        $data=[
            "periodo"=>"2024"
        ];



        $response = $this->post(route('periodos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('periodos.index'));

    }


    public function test_delete_periodo():void{
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
        Periodo::create([
            'clave'=>"2024"
        ]);

        $periodo=Periodo::find("2024");
       
        $this->assertNotNull($periodo);

        $acceso->assertStatus(302)->assertRedirect(route('home'));
        $response=$this->delete(route('periodos.destroy',$periodo->clave));
        $response->assertRedirect(route('periodos.index'));

        $this->assertDatabaseMissing('periodo', ['clave' => $periodo->clave]);

    }
}
