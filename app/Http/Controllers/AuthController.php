<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    private $DB;
    public function __construct()
    {
        $this->DB = DB::connection('mysql');
    }
    public function register()
    {
        return [
            'message' => 'Success',
            'data' => User::all(),
        ];
    }
}
