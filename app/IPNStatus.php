<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPNStatus extends Model
{
    //

    protected $fillable = [
        'payload',
        'status',
    ];
}
