<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingresos_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['idingreso', 'idarticulo', 'cantidad', 'precio'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
