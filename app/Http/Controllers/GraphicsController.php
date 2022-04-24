<?php


namespace App\Http\Controllers;


use App\Models\Match;
use App\Models\Message;
use App\Models\User;

class GraphicsController
{
    public function getGenders(){
        $male = User::query()->where('gender', "=", "male")->count();
        $female = User::query()->where('gender', "=", "female")->count();

        $response = [
            'male' => $male,
            'female' => $female
        ];

        return response($response, 201);
    }

    public function getAge(){
        $between18 = User::query()->whereBetween('age', [18,30])->where('gender','female')->count();
        $between31 = User::query()->whereBetween('age', [31,100])->where('gender','female')->count();

        $male18 = User::query()->whereBetween('age', [18,31])->where('gender','male')->count();
        $male31 = User::query()->whereBetween('age', [31,100])->where('gender','male')->count();

        $response = [
            '18-31-female' => $between18,
            '31-100-female' => $between31,

            '18-31-male' => $male18,
            '31-100-male' => $male31,
        ];

        return response($response, 201);

    }

    public function getZero(){
        return Match::query()->where('status', 0)->count();
    }
}
