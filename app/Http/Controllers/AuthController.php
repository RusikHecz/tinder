<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreRequest;

use App\Models\SaveImage;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function create(StoreRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $data['image'] = $request['image'];
        $user = User::firstOrCreate(['email' => $data['email']],$data);
        event(new Registered($user));

        $this->service->store($data);

        $token = $user->createToken('remember_token')->plainTextToken;

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

    public function updateProfile(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255', 'min:2'],
//                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'image' => ['nullable', 'image'],
                'tag_ids' => 'nullable|array',
                'tag_ids.*' => 'nullable|integer|exists:tags,id',
            ]);

            $user = User::find($id);

            $user->name = $data['name'];

            if (isset($request['image'])) {
                $gallery = $request->file('image');

                $saveImage = SaveImage::sv($gallery);

                $user -> image = $saveImage;
            }

            $tagIds = $data['tag_ids'];
            unset($data['tag_ids']);
            $user->tags()->sync($tagIds);
            $user->save();

            return response()->json(['message' => 'success']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

}
