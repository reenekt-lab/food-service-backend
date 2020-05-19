<?php


namespace App\Http\Controllers\SanctumAuth;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController
{
    use AuthUtilities;

    /**
     * Authenticating user's model
     *
     * @var string|Model
     */
    protected $userModel = User::class;

    /**
     * User login
     *
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validates login request
        $this->validateLoginRequest($request);

        $credentials = $request->only([$this->username(), 'password']);

        // Authentication attempt
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $abilities = $user->getSanctumAbilities();
            /** @var \Laravel\Sanctum\NewAccessToken $token */
            $token = $user->createToken('auth-token', $abilities);
            return $token->plainTextToken;
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
