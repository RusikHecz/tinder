<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tag;
use App\Service\UserServices;

class BaseController extends Controller
{
    public $service;

    public function __construct(UserServices $service)
    {
        $this->service = $service;
    }
}
