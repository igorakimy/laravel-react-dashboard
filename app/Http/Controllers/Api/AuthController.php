<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Invitation\ShowInvitation;
use App\Data\Auth\RegisterData;
use App\Data\Auth\UserData;
use App\Exceptions\InvitationException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

final class AuthController extends ApiController
{
    public function __construct(
       private readonly ShowInvitation $showInvitationAction,
    ) {
    }

    /**
     * User registration.
     *
     * @param  Request  $request
     * @param  string  $token
     *
     * @return Response
     * @throws InvitationException
     */
    public function register(Request $request, string $token)
    {
        $data = RegisterData::fromRequest($request);

        $invitation = $this->showInvitationAction->handle($token);

        /** @var User $user */
        $user = User::query()->create([
            'first_name' => $data->first_name,
            'last_name'  => $data->last_name,
            'email'      => $data->email,
            'password'   => $data->password,
        ]);

        $user->assignRole(...$invitation->allowedRoles);

        $invitation->update([
            'invitee_id' => $user->id,
            'accepted_at' => Carbon::now(),
        ]);

        $token = $user->createToken('main')->plainTextToken;

        auth()->login($user);

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
