<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{    
    protected $fillable = [
        'url', 
        'title',
        'image',
        'description'
    ];
    
}
