<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Proveedor;
use App\User;

class Persona extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'tipo_documento', 'num_documento', 'direccion', 'telefono', 'email'];

    public static $rules = [
        'nombre' => 'required|string|min:5|unique:personas',
    ];

    public static function rulesUpdate($id){
        return [
            'nombre' => 'required|string|min:5|unique:personas,nombre,'. $id
        ];
    }

    public function proveedor(){
        return $this->hasOne(Proveedor::class, 'id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'id');
    }

    public function model()
    {
        return $this->hasOne('App\User', 'foreign_key', 'local_key');
    }
}
