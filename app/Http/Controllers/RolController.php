<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Spatie 
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    function _construct()
    {
        $this->middleware('permission:ver-rol|crear-rol|editar-rol|borrar-rol', ['only' => ['index']]);
        $this->middleware('permission:crear-rol', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-rol', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-rol', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Vamos a obtener los roles con una paginacion de 5
        $roles = Role::paginate(5);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Traemos todos los permisos a la view de create
        $permission = Permission::get();

        return view('roles.crear', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validamos que si el rol ya existe y no dejar campos nulos
        $request->validate([
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (DB::table('roles')->where('name', $value)->exists()) {
                        $fail('El rol ya existe.');
                    }
                }
            ],
            'permission' => 'required|array|min:1',
        ], [
            'permission.required' => 'Seleccione uno o más permisos para poder guardar el rol',
            'permission.min' => 'Seleccione uno o más permisos para poder guardar el rol',
        ]);

        //Creamos el rol y le asignamos el nombre
        $role = Role::create(['name' => $request->input('name')]);
        //Asignamos los permisos al rol
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')->with('success', 'El rol "' . $role->name .'" ha sido registrado exitosamente: ' );

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    //Buscamos el rol que vamos actualizar y lo mandamos a la vista
    $role = Role::find($id);
    $permission = Permission::all();//Retornamos todos los permisos
    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('roles.editar', compact('role', 'permission', 'rolePermissions'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validamos que el no se dejen campos en blanco
        $this->validate($request, ['name' => 'required', 'permission' => 'required']);
        //Buscamos el que vamos actualizar
        $role = Role::find($id);
        //Actualizamos el nombre
        $role->name = $request->input('name');
        $role->save();//Guardamos
        //Asiganmos los nuevos permisos que se actualizaron
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')->with('success', 'El rol" ' . $role->name .'" ha sido actualizado exitosamente: ' );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Buscamo el rol que vamos a eliminar y lo eliminamos
        DB::table('roles')->where('id', $id)->delete();
        return redirect()->route('roles.index')->with('success', 'El rol ha sido eliminado exitosamente.');   
    }
}