<?php


namespace App\Http\Controllers\BaseController;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Service\PostServices;

class BaseController extends Controller
{
    public $service;

    public function __construct(PostServices $service)
    {
        $this->service = $service;
    }
}
