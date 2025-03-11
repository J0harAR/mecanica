<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//Spatie
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //Validaciones del modelo

    //Create
    public static $createRules=[
        'name' => 'required',
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[\w\.-]+@itoaxaca\.edu\.mx$/'],
        'password' => 'required | same:confirm-password',
        'roles' => 'required'
    ];
    
    //Update
    public static function updateRules($id){
        return[
        'name' => 'required',
        'email' => 'required | email |regex:/^[\w\.-]+@itoaxaca\.edu\.mx$/|unique:users,email,' . $id,
        'password' => 'same:confirm-password',
        'roles' => 'required'
        ];

    }  
   

}
