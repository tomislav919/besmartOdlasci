<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUser extends Model
{
    protected $fillable = [
        'userKeyId', 'userId',
    ];
}
