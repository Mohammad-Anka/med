<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Traits\ApiTrait;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
class AuthController extends Controller
{  use SendsPasswordResetEmails;
    use ApiTrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','forgotPassword']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'firebaseToken'=>'required|string'
        ]);
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);
        if (!$token) {

            return $this->returnJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ],401);
           
        }
        $user=new User();
        
        $user = User::find(auth('api')->user()->id);
        $user ->firebaseToken=$request->firebaseToken;
        $user->update();
     return   $this-> returnJson([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
        

    }
    public function forgotPassword(Request $request)
    {
      $this->sendResetLinkEmail($request);
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|required_with:email_confirmation|same:email_confirmation',
            'email_confirmation' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|string|min:6',
             'gender'=>'required',
            'firebaseToken'=>'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type_plane'=>$request->type_plane,
            'firebaseToken'=>$request->firebaseToken,
            'gender'=>$request->gender
        ]);

        $token = auth('api')->login($user);
        return $this->returnJson([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                
                'type' => 'bearer',
            ]
            ]);
    }
    public function me()
    {
        return $this->returnJson(auth('api')->user());
    }
    public function logout()
    {
        auth('api')->logout();
        return $this->returnJson([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
 
    }

    public function refresh()
    {
       return $this->returnJson([
        'status' => 'success',
        'user' => auth('api')->user(),
        'authorisation' => [
            'token' => auth('api')->refresh(),
            'type' => 'bearer',
        ]
        ]);
    }

  

   

}
