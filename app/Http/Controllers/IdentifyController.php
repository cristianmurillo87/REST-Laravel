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


class IdentifyController extends Controller
{

        private $atipicaController;
        private $clienteController;
        private $ladoController;
        private $manzanaController;
        private $predioController;
        private $terrenoController;
   

   
    public function __construct(){
            $this->atipicaController = new AtipicaController();
            $this->clienteController = new ClienteController();
            $this->ladoController = new LadoController();  
            $this->manzanaController = new ManzanaController();
            $this->predioController = new PredioController();
            $this->terrenoController = new TerrenoController();
              
    }
   
   private function getTerreno($x, $y){
       $terreno = $this->terrenoController->identify($x, $y);
       
       foreach ($terreno as $t) {
            $codigo = $t->cod_predio;
            $t->predios = $this->predioController->find($codigo);
            $t->atipicidades = $this->atipicaController->find($codigo);
            $t->suscriptores = $this->clienteController->find($codigo);
        }
        
       return $terreno;
   }
   
   private function getManzana ($x, $y){
       $manzana = $this->manzanaController->identify($x, $y);
       
       foreach ($manzana as $m) {
           $cod_manzana = $m->cod_manzan;
           $m->lados = $this->ladoController->find($cod_manzana, true);
       }
       
       return $manzana;
   }
    
   
    public function identifyTerreno($x, $y){
        $terreno = $this->getTerreno($x, $y);
        return response()->json(array('success'=>'true','data' => $terreno),200);
    }
    
    public function identifyManzana($x, $y){
        $manzana = $this->getManzana($x, $y);
        return response()->json(array('success'=>'true','data' => $manzana),200);
    }
    
    public function all($x, $y){
        $terreno = $this->getTerreno($x, $y);
        $manzana = $this->getManzana($x, $y);
        $data = array('terrenos'=>$terreno, 'manzana' =>$manzana );
        return response()->json(array('success'=>'true','data' => $data),200);
       
    }
}
