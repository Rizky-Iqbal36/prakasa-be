<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function register()
    {
        return [
            'message' => 'Success'
        ];
    }
}