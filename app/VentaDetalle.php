<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ventas_detalle';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idventa', 'idarticulo', 'cantidad', 'precio', 'descuento'
    ];


}
