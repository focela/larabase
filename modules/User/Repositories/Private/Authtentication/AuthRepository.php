<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\User\Repositories\Private\Authtentication;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Modules\User\Entities\User;
use Illuminate\Http\JsonResponse;
use Focela\Laratrust\Laravel\Facades\Laratrust;
use Focela\Laratrust\Checkpoints\ThrottlingException;
use Focela\Laratrust\Checkpoints\NotActivatedException;

class AuthRepository implements AuthInterface
{
    /**
     * @inheritdoc
     */
    public function login($account, $password, $remember = false)
    {
        try {
            $field = filter_var($account, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $loggedIn = Laratrust::authenticate([
                $field     => $account,
                'password' => $password,
            ], $remember);

            if (! $loggedIn) {
                return response()->json([
                    'message' => __('core::messages.invalid_credentials'),
                    'code'    => 401,
                ], Response::HTTP_UNAUTHORIZED);
            }

            $http     = new Client(['verify' => false]);
            $response = $http->post(url('/oauth/token'), [
                'form_params' => [
                    'client_id'     => config('passport.personal_access_client.id'),
                    'client_secret' => config('passport.personal_access_client.secret'),
                    'grant_type'    => 'password',
                    'username'      => $loggedIn->email,
                    'password'      => $password,
                ],
            ]);

            $authResult = json_decode((string) $response->getBody());

            return response()->json([
                'data' => [
                    'access_token'  => $authResult->access_token,
                    'refresh_token' => $authResult->refresh_token,
                    'expires_in'    => $authResult->expires_in,
                    'user'          => $this->refactorUserData($loggedIn),
                ],
                'message' => __('core::messages.successfully_logged'),
                'status'  => 200,
            ], Response::HTTP_OK);
        } catch (NotActivatedException $e) {
            return response()->json([
                'message' => __('core::messages.account_not_activated'),
                'code'    => 401,
            ], Response::HTTP_UNAUTHORIZED);
        } catch (ThrottlingException $e) {
            return response()->json([
                'message' => __('core::messages.account_is_blocked', ['delay' => $e->getDelay()]),
                'code'    => 401,
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Refactor user data.
     *
     * @param User $user
     *
     * @return array
     */
    private function refactorUserData($user)
    {
        $permissions = [];

        foreach ($user->roles as $role) {
            if (! is_null($role->permissions)) {
                $permissions = array_merge($permissions, $role->permissions);
            }
        }

        return [
            'id'          => $user->id,
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'username'    => $user->username,
            'email'       => $user->email,
            'phone'       => $user->phone,
            'avatar'      => $user->avatar,
            'permissions' => empty($user->permissions) ? $permissions : $user->permissions,
            'last_login'  => $user->last_login,
            'created_at'  => $user->created_at,
            'updated_at'  => $user->updated_at,
            'roles'       => $user->roles,
            'activation'  => $user->activation,
        ];
    }

    /**
     * Get the authenticated user.
     *
     * @return JsonResponse
     */
    public function user()
    {
        return response()->json($this->refactorUserData(auth()->user()));
    }
}
