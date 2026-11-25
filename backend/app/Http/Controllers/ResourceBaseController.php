<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseUtil;
use Illuminate\Http\Request;
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
abstract class ResourceBaseController extends AppBaseController
{
    abstract function index(Request $index);

    abstract function show($id);
}
