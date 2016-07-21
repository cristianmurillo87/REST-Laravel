<?php

namespace Estratificacion\Http\Controllers;
use Estratificacion\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Estratificacion\User as User;
use Estratificacion\Http\Requests;

class AuthenticateController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except'=>['authenticate']]);
    }
    
    public function index(){
        $user = User::all();
        return $user;
    }
    
   
    public function isAdmin(Request $request){
        $userId = $request->input('usuario');
        $admin = DB::table('usuarios')
                ->join('perfil_usuario','usuarios.id','=','perfil_usuario.id_usuario')
                ->select('perfil_usuario.administra')
                ->where('usuarios.usuario',$userId)->first();
   /*
   $consulta= "select a.oid id, a.usuario nom_usuario, a.nombre nombre, a.apellido apellido, 
b.consulta consulta, b.administra administra, b.usuario usuario from usuarios a 
inner join perfil_usuario b on a.id=b.id_usuario where a.usuario='".$usuario."' and a.contrasena=md5('".$contrasena."')";
   
   */     
        return response()->json(['isAdmin'=>$admin->administra]);
    }
    
    public function authenticate(Request $request){
        $credentials = $request->only('usuario', 'password');
        
        try{
            if (!$token = JWTAuth::attempt($credentials)){
                return response()->json(['success'=>'false', 'error'=>'Nombre de usuario o contraseña invalida'],401);
            }
        }
        catch (JWTException $e){
            return response()->json(['success'=>'false','error'=>'No fue posible crear token'],200);
        }
       
        return response()->json(compact('token'));
        
    }
   
    public function getAuthenticatedUser(){
        
        try{
            if(!$user =  JWTAuth::parseToken()->authenticate()){
                return response()->json(['success'=>'false', 'error'=>'Usuario no encontrado'],401);                
            }
        }
        
        catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['success'=>'false', 'error'=>'Token ha expirado'],$e->getStatusCode());
        }
        
        catch(Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['success'=>'false', 'error'=>'Token invalido'],$e->getStatusCode());
        }
        
        catch(Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json(['success'=>'false', 'error'=>'Token ausente'],$e->getStatusCode());
        }
        
        return response()->json(compact('user')); 
    }
}
