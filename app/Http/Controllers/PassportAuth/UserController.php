<?php


namespace App\Http\Controllers\PassportAuth;


use Illuminate\Http\JsonResponse;

class UserController
{
    /**
     * Returns current authenticated user
     *
     * @return JsonResponse
     */
    public function __invoke()
    {
        $user = auth()->user();
        return response()->json($user, 200);
    }
}
