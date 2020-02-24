<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 16:50
 */

namespace App\Http\Controllers\Api;


class UsersController extends Controller
{
    public function me()
    {
        return $this->success(auth()->user());
    }


}
