<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 
        'date', 
        'description', 
        'author_name', 
        'author_role'
    ];
}
