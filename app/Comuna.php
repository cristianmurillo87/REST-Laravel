<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    protected $table = 'comunas';
    protected $primaryKey = 'gid';
    protected $timestamps = false;
    
    public function barrios(){
        return $this->hasMany('Estratificacion\Comuna','cod_comuna','cod_comuna'), , 
    }
}
