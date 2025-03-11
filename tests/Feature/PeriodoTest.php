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
use Spatie\Permission\Models\Permission;
class PeriodoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_periodos(): void
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
            Permission::create(['name' => 'ver-periodos']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        

        $response= $this->get(route('periodos.index'))
        ->assertStatus(200)
        ->assertViewIs('periodos.index');

        
    }


    public function test_create_periodo(){
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
            Permission::create(['name' => 'crear-periodo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }

       
        
        $data=[
            "periodo"=>"2024-3",
            'fecha_inicio' => '2024-08-1',
             'fecha_final' => '2024-012-23',
        ];

        $response = $this->post(route('periodos.store'), $data); 
        $response->assertStatus(302);
        $response->assertRedirect(route('periodos.index'));

        //Periodo duplicado
        Periodo::create([
            'clave'=>'2025-3',
            'fecha_inicio'=>"2025-08-01",
            'fecha_final'=>"2025-12-20",
        ]);



        $data=[
            "periodo"=>"2025-3",
            'fecha_inicio' => '2024-08-1',
             'fecha_final' => '2024-12-23',
        ];

        $response = $this->post(route('periodos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'periodo' => 'Periodo duplicado',
        ]);

        //Periodo sin fecha de inicio
        $data=[
            "periodo"=>"2025-1",
            'fecha_inicio' => null,
            'fecha_final' => '2024-012-23',
        ];

        $response = $this->post(route('periodos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
           'fecha_inicio' => 'Fecha de inicio obligatoria.',
        ]);
        //Periodo sin fecha de fin
        $data=[
            "periodo"=>"2025-1",
            'fecha_inicio' => '2024-07-23',
             'fecha_final' => null,
        ];

        $response = $this->post(route('periodos.store'), $data); 
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'fecha_final' => 'Fecha de fin obligatoria.',
        ]);

    }



    public function test_update_periodo():void{
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
            Permission::create(['name' => 'editar-periodo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
        //Actualizacion correcta 
        Periodo::create([
            'clave'=>"2024-3",
            'fecha_inicio' => '2024-08-01',
            'fecha_final' => '2024-12-23',
        ]);
        $data=[
            'periodo'=>"2024-2",
            'fecha_inicio' => '2024-08-01',
            'fecha_final' => '2024-12-22',
        ];

        $response=$this->patch(route('periodos.update',"2024-3"),$data);
        $response->assertStatus(302);
        $response->assertRedirect(route('periodos.index'));
       
        $periodo=Periodo::where('fecha_final','2024-12-22');
        $this->assertNotNull($periodo);


        //Validacion con fecha de inicio nula
        $data=[
            'periodo'=>"2024-3",
            'fecha_inicio' =>null,
            'fecha_final' => '2024-12-22',
        ];

        $response=$this->patch(route('periodos.update',"2024-3"),$data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
           'fecha_inicio' => 'Fecha de inicio obligatoria.',
        ]);

        //Validacion con fecha de fin nula
        $data=[
            'periodo'=>"2024-3",
            'fecha_inicio' =>'2024-07-3',
            'fecha_final' => null,
        ];

        $response=$this->patch(route('periodos.update',"2024-3"),$data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'fecha_final' => 'Fecha de fin obligatoria.',
        ]);


    
    }



    public function test_delete_periodo():void{
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
            Permission::create(['name' => 'borrar-periodo']),
        ];

        $user->syncPermissions($permissions);

        foreach ($permissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission->name));
        }
       
        Periodo::create([
            'clave'=>"2024-3"
        ]);

        $periodo=Periodo::find("2024-3");
       
        $this->assertNotNull($periodo);

    
        $response=$this->delete(route('periodos.destroy',$periodo->clave));
        $response->assertRedirect(route('periodos.index'));

        $this->assertDatabaseMissing('periodo', ['clave' => $periodo->clave]);

    }
}
