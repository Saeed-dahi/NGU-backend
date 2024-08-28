<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use ApiResponser;

    /**
     * Handle API login request.
     * @param \App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginUserApi(UserLoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;

            return $this->success(
                UserResource::make($user)
            );
        } else {
            return $this->error(null, 'Invalid login', 400);
        }
    }

    /**
     * Attempt to log in the user.
     * @param \App\Http\Requests\UserLoginRequest $request
     * @return bool
     */
    protected function attemptLogin(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            return true;
        }

        Log::warning('Login attempt failed for email: ' . $request->input('email'));
        return false;
    }
}
