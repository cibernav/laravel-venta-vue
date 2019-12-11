<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Proveedor;
use App\IngresoDetalle;

class Ingreso extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingresos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idproveedor',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'subtotal',
        'igv',
        'total',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario', 'id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idproveedor', 'id');
    }

    public function items()
    {
        return $this->hasMany(IngresoDetalle::class, 'id', 'idingreso');
    }

    public static $rules = [
        'idproveedor' => 'required|integer|not_in:0',
        'tipo_comprobante' => 'required|string|not_in:0',
        'serie_comprobante' => 'required',
        'num_comprobante' => 'required',
        'impuesto' => 'required|numeric|gt:0',
        'subtotal' => 'required|numeric|gt:0',
        'igv' => 'required|numeric|gt:0',
        'total' => 'required|numeric|gt:0',
        'items' => 'required|array'
    ];

    public static $message = [
        'idproveedor.not_in' => 'El campo proveedor seleccionado es invalido.',
        'items.required' => 'El campo de detalles es requerido.'
    ];

}
