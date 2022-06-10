<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newt extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'image',
        'isImage',
        'hide'
        
    ];
    protected $hidden=['hide'];

    protected $casts = [
        'isImage' => 'boolean',
        'hide'=> 'boolean',
        
    ];
}
