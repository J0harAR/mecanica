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

        $roles = Role::paginate(5);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $permission = Permission::get();

        return view('roles.crear', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')->with('success', 'El rol "' . $role->name .'" ha sido registrado exitosamente: ' );

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $role = Role::find($id);
    $permission = Permission::all();
    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('roles.editar', compact('role', 'permission', 'rolePermissions'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, ['name' => 'required', 'permission' => 'required']);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')->with('success', 'El rol" ' . $role->name .'" ha sido actualizado exitosamente: ' );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('roles')->where('id', $id)->delete();
        return redirect()->route('roles.index')->with('success', 'El rol ha sido eliminado exitosamente.');   
    }
}