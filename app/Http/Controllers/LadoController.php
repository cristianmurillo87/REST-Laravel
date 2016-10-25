<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\JsonController as JsonController;

class LadoController extends Controller
{
    public function index($limit, $offset){

        $lim = $limit;
        $off = $offset;

        $lado = DB::table('lados')->orderBy('lado_manz')->limit($lim)->offset($off)->get();
        $total = DB::table('lados')->count();

        if(!$lado){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los lados.')),404);
        }
        
        return response()->json(array('success'=>'true','total'=> $total ,'data' => $lado),200);
    }

    public function extIndex(Request $request){

        $lim = $request->input('limit');;
        $off = $request->input('offset');

        $lado = DB::table('lados')->orderBy('lado_manz')->limit($lim)->offset($off)->get();
        $total = DB::table('lados')->count();

        if(!$lado){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los lados.')),404);
        }
        
        return response()->json(array('success'=>'true','total'=> $total ,'data' => $lado),200);
    }

    public function find($id, $byManzana = false){
        $lado = array();
        if(!$byManzana){
            $lado = DB::table('lados as l')
                    ->join('terrenos as t','l.lado_manz','=','t.lado_manz')
                    ->select(DB::raw("st_asgeojson(st_union(t.the_geom))::json as geometry, 
                            row_to_json((select j from(select l.*) as j)) as properties"))
                    ->where('l.lado_manz', strtoupper($id))->groupBy('l.gid')->get();
        }
        else{
            $lado = DB::table('lados')
                ->where('cod_manzana',strtoupper($id))->orderBy('lado_manz')->get();
        }
                
        if(!$lado){
            return [];
        }
       return $lado;
    }
    
    public function show($id){
        $lado = $this->find($id);
        
        if(count($lado)<1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No se encontró el lado de manzana solicitado.')),404);
        }
        
        $geojson = JSONController::stringToGeoJson($lado);
        
        return response()->json(array('success'=>'true', 'data'=>$geojson),200);
    }
    
        public function extJSFind($id){
       
        $lado = DB::table('lados as l')->select(DB::raw("l.lado_manz as text,'true' as leaf"))->where('l.cod_manzana','=',$id)->get();
        
        if(!$lado){
            return [];
        }
        
        $lados = array("text"=>"Manzana", 
                       "leaf"=>"false",
                       "children"=>array(
                            "text"=>$id,
                            "leaf"=>"false",
                            "children"=>$lado
                         ));
        
        return $lados;
    }
    
}
