<?php

namespace App\Helpers;

/**
 * Format response.
 */
class ResponseFormatter
{
    const CODE_SUCCESS = 200;
    const CODE_ERROR = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_NOT_FOUND = 404;
    const CODE_FAILED = 500;
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null,
        ],
        'data' => null,
    ];

    protected static function setResponse($data = null, $message = null, $code = self::CODE_SUCCESS)
    {
        self::$response['meta']['code'] = $code;
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null)
    {
        self::$response['meta']['status'] = 'success';
        return self::setResponse($data, $message);
    }

    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $code = self::CODE_ERROR)
    {
        self::$response['meta']['status'] = 'error';
        return self::setResponse($data, $message, $code);
    }

    /**
     * Give unauthorized response.
     */
    public static function unauthorized($data = null, $message = null, $code = self::CODE_UNAUTHORIZED)
    {
        self::$response['meta']['status'] = 'unauthorized';
        return self::setResponse($data, $message, $code);
    }

    /**
     * Give not found response.
     */
    public static function notFound($data = null, $message = null, $code = self::CODE_NOT_FOUND)
    {
        self::$response['meta']['status'] = 'not found';
        return self::setResponse($data, $message, $code);
    }

    /**
     * Give failed response.
     */
    public static function failed($data = null, $message = null, $code = self::CODE_FAILED)
    {
        self::$response['meta']['status'] = 'failed';
        return self::setResponse($data, $message, $code);
    }
}
