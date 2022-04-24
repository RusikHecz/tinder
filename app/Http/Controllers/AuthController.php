<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreRequest;

use App\Http\Requests\Auth\UpdateRequest;
use App\Models\SaveImage;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends BaseController
{
    public function create(StoreRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::firstOrCreate(['email' => $data['email']], $data);
        event(new Registered($user));

        $this->service->store($data);

        $token = $user->createToken('remember_token')->plainTextToken;
//        $token = DB::table('personal_access_tokens')->select('token')->where('tokenable_id', $user->id)->first();
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $data['email'])->first();

        // Check password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Неверный пароль или почта'
            ], 401);
        }

        $token = $user->createToken('remember_token')->plainTextToken;
//        $token = DB::table('personal_access_tokens')->select('token')->where('tokenable_id', $user->id)->first();
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function updateProfile(UpdateRequest $request, $user)
    {

        $data = $request->validated();

        $this->service->update($data, $user);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }

    public function findByToken(Request $request)
    {
        $user_token = $request->authorization;

        $user_id = PersonalAccessToken::findToken($user_token);
        return User::query()->where('id', $user_id->tokenable_id)->get();

    }

    public function viewUser($id)
    {
        return User::query()->with('tags')->where('id', $id)->first();
    }

    public function allUsers(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');
        return User::with('tags')->where('id', '!=', $user_id)->limit(3)->get();
    }
}
