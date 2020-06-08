<?php


namespace App\Http\Controllers\PassportAuth;


use App\Models\User;
use Illuminate\Http\JsonResponse;

class LogoutController
{
    /**
     * Returns current authenticated user
     *
     * @return JsonResponse
     */
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();
        $result = $user->token()->revoke();
        return response()->json([
            'result' => $result,
            'message' => $result ? 'logged out' : 'error while logging out'
        ], 200);
    }
}
