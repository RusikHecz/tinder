<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    protected $table = 'matches';
    protected $guarded = false;

    public function match()
    {
        return $this->belongsTo(User::class, 'target_user_id', 'id');
    }
}
