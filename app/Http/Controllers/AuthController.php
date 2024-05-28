<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\BadRequest;
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
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        $email = $body['email'];
        $user = User::where('email', $email)->first();
        if (!is_null($user))
            throw new BadRequest("Email already registered");

        $user = User::create([
            'email' => $body['email'],
            'password' => bcrypt($body['password'])
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return ['user' => $user, 'token' => $token];
    }

    public function login(Request $req)
    {
        $body = $req->json()->all();
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        if (!auth()->attempt($body))
            throw new BadRequest('Invalid email or password');

        /** @var Authenticatable $user */
        $user = auth()->user();
        $token = $user->createToken('API Token')->accessToken;

        return ['user' => $user, 'token' => $token];
    }
}
