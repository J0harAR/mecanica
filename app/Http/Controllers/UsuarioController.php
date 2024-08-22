<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
//Spatie
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;


class UsuarioController extends Controller
{
    function _construct()
    {
        $this->middleware('permission:ver-usuarios|crear-usuarios|editar-usuarios|borrar-usuarios', ['only' => ['index']]);
        $this->middleware('permission:crear-usuarios', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuarios', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-usuarios', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Retornamos los usuarios con paginacion de 5
        $usuarios = User::paginate(5);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Renderizamos la view y retornamos todos los roles
        $roles = Role::pluck('name', 'name')->all();

        return view('usuarios.crear', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validamos que no se dejen datos en blanco y validamos el regex del correo al insitucional
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[\w\.-]+@itoaxaca\.edu\.mx$/'],
            'password' => 'required | same:confirm-password',
            'roles' => 'required'

        ]);
        $input = $request->all();
        //hasheamos la password
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);//Creamos el usuario
        $user->assignRole($request->input('roles'));
        return redirect()->route('usuarios.index')->with('success', 'El usuario ha sido registrado exitosamente: ' . $user->name);
        //return redirect()->route('usuarios.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //Buscar el usuario que vamos a editar en la view
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();//Retornamos todos lo roles
        $userRole = $user->roles->pluck('name', 'name')->all();//Retornamos los roles de usuario que tiene asignado

        return view('usuarios.editar', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validamos el email para que sea institucional y ningun campo en blanco
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email |regex:/^[\w\.-]+@itoaxaca\.edu\.mx$/|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'

        ]);
        $input = $request->all();

        //hasheamos la password si no esta vacio
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));//Si es empty no se actualiza la password
        }
        //Buscamos el user  y lo actualizamos
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();//Esta parte viene de la documentacion de spatie


        $user->assignRole($request->input('roles'));//Asignamos los nuevos roles
        return redirect()->route('usuarios.index')->with('success', 'El usuario ha sido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Buscamos el usuario y lo eliminamos se borraran su roles en cascada
        User::find($id)->delete();
        return redirect()->route('usuarios.index')->with('success', 'El usuario ha sido eliminado exitosamente.');
    }
}