<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ForgotPasswordRequest as ApiForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Resources\FrontendResource;
use App\Models\FrontendUser;
use App\Notifications\Api\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    public function __construct(public FrontendUser $model)
    {
        $this->moduleName = 'User';
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $user = FrontendUser::where('email', $validatedData['email'])->first();

            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                return $this->error('Invalid credentials', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $response = [
                'message' => $this->moduleName . ' login successfully',
                'user' => new FrontendResource($user),
                'token' => $token
            ];

            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error while sending  reset password link to frontend user: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response = [
            'message' => $this->moduleName . ' logged successfully',
        ];
        return $this->showMessage($response, 200);
    }

    public function forgot_password(ApiForgotPasswordRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $status = Password::broker('frontendusers')->sendResetLink(
                $request->only('email'),
                function ($user, $token) {
                    $user->notify(new ResetPasswordNotification($token));
                }
            );

            if ($status === Password::RESET_LINK_SENT) {
                return $this->showMessage(['message' => __($status)], 200);
            } else {
                return $this->error(__($status), 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error while sending  reset password link to frontend user: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $status = Password::broker('frontendusers')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => \Hash::make($password),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return $this->showMessage(['message' => __($status)], 200);
            } else {
                return $this->error(__($status), 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error while reseting frontend user password: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
