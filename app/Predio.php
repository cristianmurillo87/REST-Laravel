<?php

namespace Estratificacion;

use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    protected $table = 'predios';
    protected $primaryKey = 'gid';
    public $timestamps = false;
    
    public function terreno(){
        return $this->belongsTo('Estratificacion\Terreno','cod_predio','cod_predio');
    }
   
}
