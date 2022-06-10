<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'subject',
        'succSend',
        'read',
        'hide',
        'isImage',
        'image',
        'appointment',
        'user_id',
        'type',
        

    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id',);
    }

    protected $hidden = ['hide'];
    protected $casts = [
        'isImage' => 'boolean',
        'hide' => 'boolean',
        'read' => 'boolean',
        'succSend' => 'boolean',

    ];
}
