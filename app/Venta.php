<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ventas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idcliente', 'idusuario', 'tipo_comprobante', 'serie_comprobante',
        'num_comprobante', 'fecha_hora', 'impuesto', 'subtotal',
        'igv', 'total', 'estado'
    ];

    public static $rules = [
        'idcliente' => 'required|integer|not_in:0',
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
        'idcliente.not_in' => 'El campo proveedor seleccionado es invalido.',
        'items.required' => 'El campo de detalles es requerido.'
    ];


}
