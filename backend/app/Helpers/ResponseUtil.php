<?php

namespace App\Helpers;

class ResponseUtil
{
    /**
     * @param string $message
     * @param mixed $data
     *
     * @param int $code
     * @return array
     */
    public static function makeResponse($message, $data, $code = 0)
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @param int $code
     * @return array
     */
    public static function makeError($message, array $data = [], $code = 0)
    {
        $res = [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
