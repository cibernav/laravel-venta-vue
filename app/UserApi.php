<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserApi extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    //Definido con softdelete
    protected $dates = ['deleted_at'];
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //mutator Name
    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }

    //selector Name
    public function getNameAttribute($valor){
        return ucwords($valor);
    }

    //mutator Email
    public function setEmailAttribute($valor){
        $this->attributes['email'] = strtolower($valor);
    }

    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }
    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }
    public static function generarVerificationToken()
    {
        return str_random(40);
    }

}
