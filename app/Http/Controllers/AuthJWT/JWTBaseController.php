<?php


namespace App\Http\Controllers\AuthJWT;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class JWTBaseController extends Controller
{
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->getTTL() * 60,
        ]);
    }
}
