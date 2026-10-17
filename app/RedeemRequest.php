<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeemRequest extends Model
{
    //
    protected $fillable = ['user_id', 'points', 'status', 'approval_date', 'notes', 'beneficiary_id'];

    public function userDetail()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
