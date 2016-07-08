<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;


class Terreno extends Model
{
    protected $table = 'terrenos';
    protected $timestamps = false;
    
    public function predios(){
        return $this->hasMany('Estratificacion\Predio');
    }
    
    public function manzana(){
        return $this->belongsTo('Estratificacion\Manzana','cod_manzan','cod_manzan');
    }
    
    public function lado(){
        return $this->belongsTo('Estratificacion\Lado','lado_manz','lado_manz');
    }
    
    public function atipica(){
        return $this->hasOne('Estratificacion\Atipica');
    }
}
