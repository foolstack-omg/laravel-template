<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\WeappAuthorizationRequest;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    public function test(){
        throw new CommonException('Hello world!');
    }

    public function weappStore(WeappAuthorizationRequest $request)
    {
        $code = $request->code;

        // 根据 code 获取微信 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($code);

        // 如果结果错误，说明 code 已过期或不正确，返回 401 错误
        if (isset($data['errcode'])) {
            throw new CommonException('code 不正确', 401);
        }

        // 找到 openid 对应的用户
        $user = User::query()->where('weapp_openid', $data['openid'])->first();

        $attributes['weixin_session_key'] = $data['session_key'];

        $attributes['name'] = $request->name;
        $attributes['avatar_url'] = $request->avatar_url;
        $attributes['gender'] = $request->gender ?? 0;
        $attributes['city'] = $request->city ?? '';
        $attributes['province'] = $request->province ?? '';
        $attributes['country'] = $request->country ?? '';

        if (!$user) {
            $attributes['weapp_openid'] = $data['openid'];
            $user = User::query()->create($attributes);
        }else{
            // 更新用户数据
            $user->update($attributes);
        }


        // 为对应用户创建 JWT
        $token = Auth::guard('api')->fromUser($user);

        return $this->respondWithToken($token);
    }

    // 注册
    public function register(Request $request)
    {

        $attributes['name'] = $request->name ?? '';
        $attributes['avatar_url'] = $request->avatar_url ?? '';
        $attributes['phone'] = $request->phone;

        $attributes['salt'] = str_random(6);
        $attributes['password'] = md5($request->password.$attributes['salt']);

        if(User::query()->where('phone', $request->phone)->exists() ) {
            return $this->failed('手机号码已被注册');
        }

        User::query()->create($attributes);

        return $this->success();

    }
    // 登陆
    public function login(Request $request) {
        $user = User::query()->where('phone', $request->phone)->first();
        if(empty($user)) {
            return $this->failed('用户不存在');
        }

        if($user->password != md5($request->password.$user->salt)) {
            return $this->failed('账号密码不正确');
        }

        $token = Auth::guard('api')->fromUser($user);

        return $this->respondWithToken($token);


    }

    public function update(Request $request)
    {
        $token = Auth::guard('api')->refresh();

        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->success();
    }

    protected function respondWithToken($token)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expired_at' => time() + Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
