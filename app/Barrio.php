<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    protected $table = 'barrios';
    protected $primaryKey = 'gid';
    protected $timestamps = false;
    
    public function manzanas(){
        return $this->hasMany('Estratificacion\Manzana');
    }
    
    public function comuna(){
        return $this->belongsTo('Estratificacion\Comuna','cod_comuna','cod_comuna');
    }
}
