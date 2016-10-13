<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\AtipicaController as AtipicaController;
use Estratificacion\Http\Controllers\LadoController as LadoController;
use Estratificacion\Http\Controllers\ManzanaController as ManzanaController;
use Estratificacion\Http\Controllers\PredioController as PredioController;
use Estratificacion\Http\Controllers\TerrenoController as TerrenoController;

class ExtIdentifyController extends Controller
{

        private $clienteController;
        private $ladoController;
        private $predioController;
   

   
    public function __construct(){
            $this->clienteController = new ClienteController();
            $this->ladoController = new LadoController();  
            $this->predioController = new PredioController();
              
    }
   
    public function identify($terreno, $manzana){
        $predios = $this->clienteController->extJSfind($terreno);
        $manzana = $this->ladoController->extJSfind($manzana);
        $clientes = $this->predioController->extJSfind($terreno);
        
        $data = array($clientes, $manzana, $predios);
        
        return response()->json($data,200);

    }
}