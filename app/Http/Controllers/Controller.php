<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function validateReq(Validator $validator)
    {
        if ($validator->fails()) {
            $errors = $validator->errors();
            $merged_messages = '';
            foreach ($errors->all() as $index => $error) {
                $merged_messages .= ($index !== 0 ? " | " : "") . $error;
            }
            return [
                'result' => 0,
                'desc' => 'Payload doesn\'t pass validation',
                'reason' => $errors,
                'messages' => $merged_messages,
            ];
        }

        return null;
    }
}
