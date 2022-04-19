<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'nullable|integer|exists:tags,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $tagIds = $data['tag_ids'];

        unset($data['tag_ids']);

        $user->tags()->attach($tagIds);

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

    public function updateProfile(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255', 'min:2'],
//                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'image' => ['nullable', 'image'],
                'tag_ids' => 'nullable|array',
                'tag_ids.*' => 'nullable|integer|exists:tags,id',
            ]);

            $user = User::find($request->user()->id);

            $user->name = $data['name'];
//            $user->email = $data['email'];

            if ($data['image'] && $data['image']->isValid()) {
                $file_name = time() . '.' . $data['image']->extension();
                $data['image']->move(public_path('images'), $file_name);
                $path = "public/images/$file_name";
                $user->image = $path;
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
