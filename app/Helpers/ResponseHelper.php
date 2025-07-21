<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    
    public static function error($message = 'Error', $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
    
    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }
    
    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }
    
    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }
    
    public static function validationError($errors, $message = 'Validation failed')
    {
        return self::error($message, 422, $errors);
    }
}