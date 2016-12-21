<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Http\Controllers\JsonController as JsonController;

class ComunaController extends Controller
{
    public function find($id){
        $comuna = DB::table('comunas as c')
                  ->select(
                       DB::raw("st_asgeojson(c.the_geom)::json as geometry"),
                       DB::raw("row_to_json((select j from (select c.gid, c.cod_comuna,c.nombre) as j)) as properties")
                   )
                  ->where('c.cod_comuna', '=', strtoupper($id))
                  ->orWhere('c.nombre', '=', strtoupper($id))->get();
                   
        if(!$comuna){
            return [];
        }
        
        return $comuna;
    }
    
    public function show($id){
        $comuna = $this->find($id);
        
        if(count($comuna) < 1){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Comuna no encontrada.')),404);
        }
        
        $geojson = JSONController::stringToGeoJson($comuna);
        
        return response()->json(array('success'=>'true', 'data'=>$geojson),200);

    }

    public function _list(Request $request){
      $req = '%'. $request->input('id').'%';
      $comuna = DB::table('comunas as c')
                ->select('c.cod_comuna', 'c.nombre')
                ->where('c.cod_comuna', 'ilike', $req)
                ->orWhere('c.nombre', 'ilike', $req)->orderBy('c.cod_comuna')->get();
      if(!$comuna){
        return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Comuna no encontrada.')),404);
      }

      return response()->json(array('success'=>'true', 'data'=>$comuna),200);
    }  
}
