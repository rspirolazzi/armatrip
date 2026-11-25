<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseUtil;
use Response;
use App\Helpers\Errors;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    /**
     * @param $result
     * @param string $message
     * @return mixed
     */
    public function sendResponse($result, $message = '')
    {
        return Response::json(ResponseUtil::makeResponse($message, $result, 0));
    }

    /**
     * @param $error
     * @param $errorCode
     * @param int $code
     * @return mixed
     */
    public function sendError($error, $errorCode = null, $code = 404)
    {
        if (is_null($errorCode)) {
            $errorCode = Errors::$E_UNKNOWN;
        }
        return Response::json(ResponseUtil::makeError($error, [], $errorCode), $code);
    }
}
