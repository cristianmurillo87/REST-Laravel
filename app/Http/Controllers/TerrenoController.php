<?php

namespace Estratificacion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

use DB;

use Estratificacion\Http\Requests;
use Estratificacion\Terreno;

class TerrenoController extends Controller
{
    
  /*  public function __construct(){
        $this->middleware('jwt.auth');
    }*/
    /*
    *Devuelve informacion geografica y alfanumerica relacionada con el terreno solicitado mediante la interseccion geografica 
    *con un un punto con coordenadas x y
    *@param $x -> coordenada x del punto del que se desea obtener informacion
    *@param $y -> coordenada y del punto del que se desea obtener informacion
    *@return Response
    */
    
    
    public function identify($x, $y){
        if(is_numeric($x) && is_numeric($y)){
            
            $terreno = DB::select(DB::raw("select terrenos.gid as gid, terrenos.cod_predio as cod_predio, 
                                terrenos.cod_manzan as cod_manzan, tipo_actividad.nombre as actividad, 
                                terrenos.direccion as direccion, terrenos.lado_manz as lado_manz, 
                                st_astext(terrenos.the_geom) as geometry, lados.estrato from terrenos 
                                left join tipo_actividad on terrenos.cod_act = tipo_actividad.cod_act 
                                left join lados on terrenos.lado_manz = lados.lado_manz
                                where st_intersects(terrenos.the_geom, st_geomfromtext('POINT($x $y)',97664))"));
           
            return $terreno;            
        }
        
        return [];
    
    }
    
    /*
    *Devuelve informacion geografica y alfanumerica relacionada con el terreno solicitado.
    *@param $id -> numero matriz del terreno
    *@return array de objetos json
    */
    
    public function find($id){
         
          $terreno = DB::select(
                        DB::raw("select st_asgeojson(t.the_geom) as geometry, 
                                row_to_json((select j from (select t.gid, t.cod_predio, p.num_predia, p.cod_pred_n, p.direccion,t.cod_act,t.cod_manzan,t.lado_manz,l.estrato) as j)) as properties 
                                from terrenos t inner join lados l on t.lado_manz = l.lado_manz 
                                inner join predios p on p.cod_predio = t.cod_predio where p.cod_predio ='$id' or
                                p.cod_pred_n = '$id' or p.num_predia = '$id' limit 1"));
          
          if(!$terreno){
              return [];
          }
          
          return $terreno;
    }
    
    
    /*
    *Despliega la lista de terrenos en forma de informacion para paginacion.
    *@param $limit -> cantidad de registros que debe devolver la consulta.
    *@param $offset -> id (gid) a partir del cual se generan los $limit resultados.
    *@return Response.   
    */
    public function index($limit, $offset){
        $terreno = DB::table('terrenos')
                    ->leftJoin('tipo_actividad','terrenos.cod_act','=','tipo_actividad.cod_act')
                    ->select('terrenos.gid as gid','terrenos.cod_predio as cod_predio','terrenos.cod_manzan as cod_manzan',
                             'tipo_actividad.nombre as actividad', 'terrenos.direccion as direccion', 'terrenos.lado_manz as lado_manz')
                    ->orderBy('gid')->limit($limit)->offset($offset)->get();
                    
         $total = DB::table('terrenos')->count();

        if(!$terreno){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'Error al consultar los terrenos.')),404);
        }
        
        
        return response()->json(array('success'=>'true','total'=> $total ,'data' => $terreno),200);
        
    }
    
    /*
    *Devuelve informacion geografica y alfanumerica relacionada con el terreno solicitado.
    *ejecutando inicialmente el metodo find
    *@param $id -> numero matriz del terreno
    *@return Response
    */
    public function show($id){

        $terreno = $this->find($id);
                
        if( count($terreno) < 1 ){
            return response()->json(array('success'=>'false', 'errors'=>array('reason'=>'No fue posible ubicar el terreno solicitado.')),404);
        }
        
        $features= array();
        foreach ($terreno as $t) {
            array_push($features,
                array(
                    "type"=>"Feature",
                    "geometry" => json_decode($t->geometry,true),
                    "properties" =>json_decode($t->properties,true)
                )
            );
        }
        
        $geojson = array(
            "type" =>"FeatureCollection",
            "features" => $features
        );

        return response()->json(array('success'=>'true', 'data'=>$geojson), 200);
        
    }                  

}

