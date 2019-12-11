<?php

namespace App;

use Articulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    public static $rules = [
        'nombre' => 'required|min:5'
    ];

    public static function rulesUpdate($id){
        return [
            'nombre' => 'required|min:5|unique:categorias,nombre,'. $id
        ];
    }

    public function articulos(){
        return $this-hasMany(Articulo::class, 'id');
    }
}
