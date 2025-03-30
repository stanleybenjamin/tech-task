<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('apiSuccess')) {
    /**
     *
     * @param array|object $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    function apiSuccess(
        array|object $data = [],
        string $message = "",
        int $code = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}

if (!function_exists('apiError')) {
    /**
     *
     * @param array|object $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    function apiError(
        array|object $data = [],
        string $message = "",
        int $code = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
