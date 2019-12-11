<?php

namespace App;

use Categoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articulo extends Model
{
    use SoftDeletes;
    //
    protected $dates = ['deleted_at'];

    const DELETED_AT = 'a.deleted_at';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articulos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['idcategoria', 'codigo', 'nombre', 'precio_venta', 'stock', 'descripcion', 'condicion'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    public function categoria(){
        $this->belongsTo(Categoria::class, 'id');
    }

    public static $rules = [
        'idcategoria' => 'required|integer|gt:0',
        'nombre' => 'required|unique:articulos,nombre',
        'precio_venta' => 'required|numeric|gt:0',
        'stock' => 'required|integer|gt:0'

    ];

    public static $message = [
        'idcategoria.gt' => 'Debe seleccionar una categoria'
    ];

    public static function rulesUpdate($id){
        return [
            'idcategoria' => 'required|integer|gt:0',
            'nombre' => 'required|unique:articulos,nombre,'. $id,
            'precio_venta' => 'required|numeric|gt:0',
            'stock' => 'required|integer|gt:0'
        ];
    }

}
