<?php


namespace App\Http\Controllers;


use App\Models\Tag;

class TagController
{
    public function show(Tag $tag)
    {
        return Tag::query()->get();
    }
}
