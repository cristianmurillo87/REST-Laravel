<?php

namespace Estratificacion;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $timestamps = false;
    
    protected $fillable = [
        'usuario', 'nombre','apellido', 'contrasena', 'id_estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'contrasena', 'id_estado'
    ];
    
}
