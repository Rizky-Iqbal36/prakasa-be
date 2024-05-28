<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\BadRequest;
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
            throw new BadRequest('Payload doesn\'t pass validation', [
                'messages' => $merged_messages,
                'reason' => $errors,
            ]);
        }

        return null;
    }

    function searchValueInArray($array_to_search, $search_value)
    {
        $index = array_search($search_value, $array_to_search);
        $index_type = gettype($index);
        $data_found = $index_type === "integer";
        return [
            'index' => $index,
            'index_type' => $index_type,
            'data_found' => $data_found,
        ];
    }

    function searchArrayOfObject($property_value, $property_name, $payloads)
    {
        $index = array_search($property_value, array_column($payloads, $property_name));
        $index_type = gettype($index);
        $data_found = $index_type === "integer";
        return [
            'index' => !$data_found ? -1 : $index,
            'index_type' => $index_type,
            'data_found' => $data_found,
            'data' => $data_found ? $payloads[$index] : null
        ];
    }
}
