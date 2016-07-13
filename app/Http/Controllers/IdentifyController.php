<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\TerrenoController as TerrenoController;
use Estratificacion\Http\Controllers\PredioController as PredioController;

class IdentifyController extends Controller
{
    public function identifyTerreno($x, $y){
        $terrenoController = new TerrenoController();
        $predioController = new PredioController();
        
        $terreno = $terrenoController->identify($x, $y);
        
        $data = array();
                
        foreach ($terreno as $t) {
            $codigo = $t->cod_predio;
            $predios = $predioController->find($codigo);
            $t->predios = $predios;
        }
        
        
        return response()->json(array('success'=>'true','data' => $terreno),200);
        
    }
}
