<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
   use ApiTrait;
}
