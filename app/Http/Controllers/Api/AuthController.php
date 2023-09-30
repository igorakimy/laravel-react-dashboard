<?php

namespace App\Http\Controllers\Api;

use App\Data\Auth\UserData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

final class AuthController extends ApiController
{
    /**
     * User registration.
     *
     * @param  RegisterRequest  $request
     *
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        /** @var User $user */
        $user = User::query()->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user?->createToken('main')->plainTextToken;

        return response(compact('user', 'token'));
    }

    /**
     * User login.
     *
     * @param  LoginRequest  $request
     *
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if ( ! auth()->attempt($data)) {
            return response([
                'message' => 'Invalid credentials',
            ], 401);
        }

        /** @var User $user */
        $user  = auth()->user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => UserData::from($user),
            'token' => $token,
        ]);
    }

    /**
     * Get the current user.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response(UserData::from($user));
    }

    /**
     * Logout the user.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function logout(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $user?->tokens()->delete();

        return response()->noContent();
    }
}
