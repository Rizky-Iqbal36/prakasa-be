<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ResponseMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse && $response->getStatusCode() === 200) {
            $data = $response->getData();

            $standardResponse = [
                'success' => true,
                'message' => $data->message ?? 'Operation successful',
                'data' => $data->data ?? $data,
            ];
            if (array_key_exists('message', $data)) {
                if (count((array) $data) === 1)
                    unset($standardResponse['data']);
                unset($data->message);
            }

            $response->setData($standardResponse);
        }
        return $response;
    }
}
