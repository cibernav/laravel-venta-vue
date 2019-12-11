<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Rol extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    public function users(){
        return $this->hasMany(User::class);
    }
}
