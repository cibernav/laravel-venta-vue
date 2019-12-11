<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Persona;

class Proveedor extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proveedores';

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
    protected $fillable = ['id', 'contacto', 'telefono_contacto'];

    public function persona(){
        return $this->belongsTo(Persona::class);
    }
}
