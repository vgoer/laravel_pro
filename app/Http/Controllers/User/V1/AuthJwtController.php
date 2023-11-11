<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthJwtController extends Controller
{
    public function register(Request $request)
    {
        $params = $request->all();

        if (empty($params['name'])) {
            return error(500100);
        }
        if (empty($params['password'])) {
            return error(500101);
        }
        if (empty($params['email'])) {
            return error(500102);
        }

        $salt = salt(20);
        $credentials = [
            'name' => $params['name'],
            'salt' => $salt,
            'password' => Hash::make($params['password'].$salt),
            'email' => $params['email'],
        ];

        $user = User::create($credentials);

        if ($user) {
            return success();
        }

        return error();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $params = $request->all();

        if (empty($params['password'])) {
            return error(500101);
        }
        if (empty($params['email'])) {
            return error(500102);
        }

        $count = User::query()
                ->where('email', $params['email'])
                ->count();

        if ($count != 1) {
            return error(500103);
        }

        $user = User::query()
                ->where('email', $params['email'])
                ->first();

        // 验证密码是否正确
        if ((! Hash::check($params['password'].$user->salt, $user->password))) {
            return error(500103);
        }

        $token = auth('api')->login($user);

        if ($token) {
            return $this->responseWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        return $this->responseWithToken(JWTAuth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * 响应
     */
    private function responseWithToken($token)
    {
        $response = [
            'access_token' => 'bearer '.$token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];

        return response()->json($response);
    }
}
