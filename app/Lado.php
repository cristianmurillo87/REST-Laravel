<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Lado extends Model
{
    protected $table = 'lados';
    protected $timestamps = false;
    
    public function terrenos(){
        return $this->hasMany('Estratificacion\Terreno');
    }
    
    public function manzana(){
        return $this->belongsTo('Estratificacion\Manzana','lado_manz','lado_manz');
    }
}
