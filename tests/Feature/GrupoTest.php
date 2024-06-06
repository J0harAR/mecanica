<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use App\Models\Practica;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Persona;
use App\Models\Asignatura;

class GrupoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_grupos(): void
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
        

        $response= $this->get(route('grupos.index'))
        ->assertStatus(200)
        ->assertViewIs('grupos.index');
    }

    public function test_create_grupos():void{
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
       
        $response= $this->get(route('grupos.create'))
        ->assertStatus(200)
        ->assertViewIs('grupos.create');

        Asignatura::create([
            'clave'=>'IA',
            'nombre'=>'Inteligencia artificial'
        ]);
        $asignatura=Asignatura::find('IA');
        $this->assertNotNull($asignatura);
       
        $data=[
            'clave_grupo'=>"1A",
            'asignatura'=>$asignatura->clave
        ];

        $response = $this->post(route('grupos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('grupos.index'));
    }
}
