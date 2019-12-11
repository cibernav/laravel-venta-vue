<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Rol;
use App\Persona;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'password', 'condicion', 'idrol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static $rules = [
        'nombre' => 'required|string|min:5|unique:personas',
        'username' => 'required|string|min:5|unique:users',
        'password' => 'required|string|min:6',
        'idrol' => 'required|integer|gt:0'
    ];

    public static function rulesUpdate($id){
        return [
            'nombre' => 'required|string|min:5|unique:personas,nombre,'. $id,
            'username' => 'required|string|min:5|unique:users,username,'. $id,
            'password' => 'nullable|string|min:6',
            'idrol' => 'required|integer|gt:0'
        ];
    }

    public static $message = [
        'idrol.gt' => 'Debe seleccionar un rol.',
        'username.required' => 'El campo usuario es requerido.'
    ];

    public function rol(){
        return $this->belongsTo(Rol::class, 'idrol', 'id');
    }

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    //mutator Name
    public function setUserNameAttribute($valor){
        $this->attributes['username'] = strtolower($valor);
    }

    //selector Name
    public function getUserNameAttribute($valor){
        return ucwords($valor);
    }

    public function hasRole(array $roles){
        foreach ($roles as $role) {
            if($this->rol->nombre === $role){
                return true;
            }
        }

        return false;
    }

}
