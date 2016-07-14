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
            $lado = DB::table('lados')
                ->leftJoin('terrenos', 'lados.lado_manz','=','terrenos.lado_manz')
                ->select('lados.*', DB::raw('st_astext(st_union(terrenos.the_geom)) as wkt'))
                ->where('lados.lado_manz',Str::upper($id))->groupBy('lados.gid')->orderBy('lados.lado_manz')->get();
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
        
        return response()->json(array('success'=>'true', 'data'=>$lado),200);
    }
    
}
