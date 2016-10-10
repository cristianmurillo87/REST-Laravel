<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;

use DB;

use Estratificacion\Http\Requests;

class LadoController extends Controller
{
    public function index($limit, $offset){
        $lado = DB::table('lados')->orderBy('lado_manz')->limit($limit)->offset($offset)->get();
        $total = DB::table('lados')->count();

        if(!$lado){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los lados.')),404);
        }
        
        return response()->json(array('success'=>'true','total'=> $total ,'data' => $lado),200);
    }
    
    public function find($id, $byManzana = false){
        $lado = array();
        if(!$byManzana){
            $lado = DB::select(DB::raw(
                "select st_asgeojson(st_union(t.the_geom))::json as geometry, 
                row_to_json((select j from(select l.*) as j)) as properties from 
                lados l inner join terrenos t on t.lado_manz = l.lado_manz where l.lado_manz = '$id' 
                group by l.gid order by l.lado_manz"
            ));
        }
        else{
            $lado = DB::table('lados')
                ->where('cod_manzana',$id)->orderBy('lado_manz')->get();
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
        
        $features = array();
        foreach ($lado as $l) {
            array_push($features,
                array(
                    "type"=>"Feature",
                    "geometry"=>json_decode($l->geometry,true),
                    "properties"=>json_decode($l->properties,true)
                )
            );
        }
        
        $geojson = array(
            "type" => "FeatureCollection",
            "features" => $features
        );
        
        return response()->json(array('success'=>'true', 'data'=>$geojson),200);
    }
    
}
