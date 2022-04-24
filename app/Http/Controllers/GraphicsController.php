<?php


namespace App\Http\Controllers;


use App\Models\Match;
use App\Models\Message;
use App\Models\User;

class GraphicsController
{
    public function getMales(){
        return User::query()->where('gender', "=", "male")->count();
    }

    public function getFemales(){
        return User::query()->where('gender', "=", "female")->count();
    }

    public function getOne(){
        return Match::query()->where('status', 1)->count();
    }

    public function getZero(){
        return Match::query()->where('status', 0)->count();
    }
}
