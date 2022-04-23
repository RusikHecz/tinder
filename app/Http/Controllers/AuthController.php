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
        $token = DB::table('personal_access_tokens')->select('token')->where('tokenable_id', $user->id)->first();
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
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('remember_token')->plainTextToken;
        $token = DB::table('personal_access_tokens')->select('token')->where('tokenable_id', $user->id)->first();
        $response = [gi
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

    public function updateProfile(UpdateRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user()->id;

        $this->service->update($data,$user);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }

    public function findByToken(Request $request)
    {
        $user_token = $request->authorization;
        $user_id = DB::table('personal_access_tokens')->where('token', $user_token)->first();
//        echo $user_id->tokenable_id;
        return User::query()->where('id', $user_id->tokenable_id)->get();

    }
}
