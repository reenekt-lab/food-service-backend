<?php


namespace App\Http\Controllers\SanctumAuth;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait AuthUtilities
{
    /**
     * Get username field name
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Validate login request
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function validateLoginRequest(Request $request)
    {
        $rules = [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $messages = []; // todo

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator->validate();
    }
}
