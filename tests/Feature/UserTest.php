<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
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
            'email' => 'alfaro@itoaxaca.edu.mx',
            'name' => 'Johan',
            'password' => 'johan1234-',
            'password_confirmation' => 'johan1234-',
        ]);
        $registroBien->assertStatus(302)->assertRedirect(route('home'));


        

        $this->assertDatabaseHas('users',['email'=>"alfaro@itoaxaca.edu.mx"]);



        //Registro repetido, en este caso email repetidos
        $registroMal = $this->post(route('register'), [
            'email' => 'alfaro@itoaxaca.edu.mx',
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
            "email" => 'test@itoaxaca.edu.mx',
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
            "email" => 'test@itoaxaca.edu.mx',
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

    /**
    * Test para la creacion de usuarios manualmente(Dashboard)
    */
    public function test_view_users(){

        Artisan::call('migrate');
        
        User::create([
            "name" =>"Test",
            "email" => 'test@itoaxaca.edu.mx',
            "password" => Hash::make('password22'),
        ]);

        $acceso = $this->post(route('login'), [
            'email' => 'test@itoaxaca.edu.mx',
            'password' => 'password22',
        
        ]);
        $acceso->assertStatus(302)->assertRedirect(route('home'));
        //Ver la tabla de usuarios
        $acceso = $this->get(route('usuarios.index'))
        ->assertStatus(200)
        ->assertViewIs('usuarios.index');
    }
    public function test_create_user(){
        
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@itoaxaca.edu.mx',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);
        $roleServicioSocial = Role::firstOrCreate(['name' => 'Servicio Social']);
        $user->assignRole($roleAdministrador);
       

        $this->post(route('login'), [
            'email' => 'test@itoaxaca.edu.mx',
            'password' => 'password22',
        ]);
        //Ver formulario de crear usuario
        $this->get(route('usuarios.create'))
        ->assertStatus(200)
        ->assertViewIs('usuarios.crear');

       
        //Creacion correcta de un usuario
        $this->post(route('usuarios.store'), [
            'name' => 'Johan',
            'email' => 'prueba@gmail.com',
            'password' => 'password23232',
            'confirm-password' => 'password23232', 
            'roles' => 'Servicio Social',
        ])->assertRedirect(route('usuarios.index'));

            
        //Creacion incorrecta de un usuario
        $registroMal = $this->post(route('register'), [
            'email' => 'prueba2@gmail.com',
            'name' => '',
            'password' => 'johan12342',
            'password_confirmation' => 'johan1234',
            'roles' => 'Servicio Social',
        ]);
        
        if (session('errors')) {           
            $registroMal->assertRedirect(route('usuarios.create'))
                ->assertSessionHasErrors([
                    'email' => __('validation.unique', ['attribute' => 'email']),
                    'password' => __('validation.same', ['attribute' => 'password']),
                    'name' => __('validation.required', ['attribute' => 'name']),
                    'role' => __('validation.required', ['attribute' => 'role']),
                ]);
        } else {          
            $registroMal->assertRedirect(route('home'));
        }


    }

    public function test_edit_user(){
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@itoaxaca.edu.mx',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);
        $roleServicioSocial = Role::firstOrCreate(['name' => 'Servicio Social']);
        $user->assignRole($roleAdministrador);
       

        $this->post(route('login'), [
            'email' => 'test@itoaxaca.edu.mx',
            'password' => 'password22',
        ]);
        //Ver formulario de editar usuario
        $this->get(route('usuarios.edit',$user->id))
        ->assertStatus(200)
        ->assertViewIs('usuarios.editar');

        //Update de forma correcta de un usuario
        $updateCorrecto = $this->patch(route('usuarios.update',$user->id), [
            'email' => 'prueba2@gmail.com',
            'name' => 'Pedro',
            'password' => '',
            'password_confirmation' => '',
            'roles' => 'Servicio Social',
        ])->assertRedirect(route('usuarios.index'));

        //Update de forma incorrecta de un usuario(Contraseñas mal)
        $registroMal = $this->patch(route('usuarios.update',$user->id), [
            'email' => 'prueba2@gmail.com',
            'name' => 'Pedro',
            'password' => 'asdasd',
            'password_confirmation' => '', 
            'roles' => 'Servicio Social',
        ]);

        $registroMal->assertRedirect(route('usuarios.edit',$user->id))
        ->assertSessionHasErrors([
            'password' => __('validation.same', ['attribute' => 'password']),
        ]);

    }
    public function test_delete_user(){
        Artisan::call('migrate');
        
        $user = User::factory()->create([
            "name" => "Test",
            "email" => 'test@gmail.com',
            "password" => Hash::make('password22'),
        ]);
        $roleAdministrador = Role::firstOrCreate(['name' => 'Administrador']);
        $user->assignRole($roleAdministrador);
        $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password22',
        ]);
        //Eliminar registro
        $userEliminar = User::factory()->create();
        $this->delete(route('usuarios.destroy', $userEliminar->id))
        ->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseMissing('users', ['id' => $userEliminar->id]);

    }

  

   
}

