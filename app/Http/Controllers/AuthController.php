<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $DB;
    public function __construct()
    {
        $this->DB = DB::connection('mysql');
    }
    
    public function register(Request $req)
    {
        $body = $req->json()->all();
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ];
        $queries_validator = Validator::make($body, $rules);
        $queries_validation = $this->validateReq($queries_validator);
        if(!is_null($queries_validation)) return $queries_validation;

        $user = User::create([
            'name' => $body['name'],
            'email' => $body['email'],
            'password' => bcrypt($body['password'])
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 'user' => $user, 'token' => $token]);
    }

    public function login(Request $req)
    {
        $body = $req->json()->all();
        $rules = [
            'email' => 'email|required',
            'password' => 'required'
        ];
        $queries_validator = Validator::make($body, $rules);
        $queries_validation = $this->validateReq($queries_validator);
        if(!is_null($queries_validation)) return $queries_validation;
        
        if (!auth()->attempt($body)) 
            return ['error_message' => 'Invalid email or password'];
        
        /** @var Authenticatable $user */
        $user = auth()->user();
        $token = $user->createToken('API Token')->accessToken;

        return ['user' => $user, 'token' => $token];

    }
}
