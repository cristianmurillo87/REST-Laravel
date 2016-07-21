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
    
   
    private function getRoles($id){
        $roles = DB::table('usuarios')
                ->join('perfil_usuario','usuarios.id','=','perfil_usuario.id_usuario')
                ->select('perfil_usuario.administra as administrar', 'perfil_usuario.consulta as consultar','perfil_usuario.usuario as ver')
                ->where('usuarios.id',$id)->first();
     
        return $roles;
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
        
        $userRoles= $this->getRoles($user->id);
        $user->roles = $userRoles; 
        return response()->json(compact('user')); 
    }
    
    public function logOut(){
        $token = JWTAuth::getToken();
        if($token){
            JWTAuth::setToken($token)->invalidate();
            return response()->json(['success'=>true, 'message'=>'Sesion terminada']);
        }
        else{
            return response()->json(['success'=>true, 'message'=>'Su sesion ya ha terminado']);
        }
    }
}
