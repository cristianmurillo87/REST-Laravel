<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'emcali_clientes';
    protected $primaryKey = 'gid';
    public $timestamps = false;
    
}
