<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait ApiTrait {

    /**
     * 成功
     * @param $data
     * @param int $status_code
     * @return mixed
     */
    public function success($data = [], $status_code = 0) {
        return response()->json([
            'status' => 1,
            'status_code' => $status_code,
            'data' => $data
        ]);
    }

    /**
     * 失败
     * @param $message
     * @param int $status_code
     * @param array $data
     * @return mixed
     */
    public function failed($message, $status_code = 0, $errors = []) {

        return response()->json([
            'status' => 0,
            'status_code' => $status_code,
            'message' => $message,
            'errors' => $errors
        ]);
    }


    /**
     * 判断是否需要json格式返回
     * @param Request $request
     * @return bool
     */
    public function expectsJson(Request $request) {
        return $request->expectsJson() || starts_with($request->getPathInfo(), '/api');
    }
}
