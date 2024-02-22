<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserTest extends TestCase
{
    
    /**
    * Test para el registro de usuarios de laravel UI.
    */
    public function test_register_ui(){
        Artisan::call('migrate');

        //El formulario carga correctamente
        $carga=$this->get(route('register'));
        $carga->assertStatus(200)->assertSee('register');

        //Un register ingresado de forma incorrecta 
        $registroMal = $this->post(route('register'),[
            'email' => 'Test User',
            'name' => '',
            'password' => '12',
            'password_confirmation' => '11',
        ]);
        $registroMal->assertStatus(302)->assertRedirect(route('register'))->
        assertSessionHasErrors([
            'email'=>__('validation.email',['attribute'=>'email']),
            'name'=>__('validation.required',['attribute'=>'name']),
            'password'=>__('validation.min.string',['attribute'=>'password','min'=>8]),
        ]);

        //Un register ingresado de forma Correcta 
        $registroBien=$this->post(route('register'),[
            'email' => 'alfaro@gmail.com',
            'name' => 'Johan',
            'password' => 'johan1234',
            'password_confirmation' => 'johan1234',
        ]);
        $registroBien->assertStatus(302)->assertRedirect(route('home'));

        $this->assertDatabaseHas('users',['email'=>"alfaro@gmail.com"]);



        //Registro repetido, en este caso email repetidos
        $registroMal = $this->post(route('register'), [
            'email' => 'alfaro@gmail.com',
            'name' => 'Johan',
            'password' => 'johan1234',
            'password_confirmation' => 'johan1234',
        ]);
        
        if (session('errors')) {           
            $registroMal->assertRedirect(route('register'))
                ->assertSessionHasErrors([
                    'email' => __('validation.unique', ['attribute' => 'email']),
                ]);
        } else {          
            $registroMal->assertRedirect(route('home'));
        }


        
    }

    public function test_login_ui(){
        Artisan::call('migrate');

        User::create([
            "name" =>"Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);

         //El formulario carga correctamente
         $carga=$this->get(route('login'));
         $carga->assertStatus(200)->assertSee('login');

        //Intentar entrar a home sin autorizacion
         $accesoMal=$this->get(route('home'));
         $accesoMal->assertStatus(302)->assertRedirect(route('login')); 


        //Acceso correcto
        $accesoCorrecto = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        
        ]);
        $accesoCorrecto->assertStatus(302)->assertRedirect(route('home'));

        //Vista del dashboard
        $vistaHome=$this->get(route('home'));
        $vistaHome->assertStatus(200)->assertSee('home');   

        
        //Acceso incorrecto
        $accesoIncorrecto = $this->post(route('login'), [
            'email' => 'test@gmail.com2',
            'password' => 'password22',
        
        ]);

        if($accesoIncorrecto->getSession()->has('errors')){
            $accesoIncorrecto->assertRedirect(route('Login'))->assertSessionHasErrors([
            'email' => __('auth.failed'),
            'password' => __('auth.failed'),
        ]);
        }else{
            $accesoIncorrecto->assertRedirect(route('home'));
        }
       


    }
}

