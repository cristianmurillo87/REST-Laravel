<?php

namespace Estratificacion\Http\Controllers;
use Estratificacion\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return response()->json([$userId]);
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
