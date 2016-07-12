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
        
        $predios = array();
                
        foreach ($terreno as $t) {
            $codigo = $t->cod_predio;
            $predio = $predioController->find($codigo);
            array_push($predios, $predio);
        }
        
        
        return response()->json(array('success'=>'true','data' => array('terreno' => $terreno, 'predios'=> $predios)),200);
        
    }
}
