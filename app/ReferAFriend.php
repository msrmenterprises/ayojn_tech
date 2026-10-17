<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferAFriend extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'user_id','email_address'
    ];

    public function userDetail()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
