<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/*
|--------------------------------------------------------------------------
| Api Response Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response that will be sent.
|
*/

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param mixed|null $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success(mixed $data = null, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param mixed $data
     * @return JsonResponse
     */
    protected function error(string $message = null, int $code = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

}
