<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
    //
    protected $fillable = ['point', 'points_by', 'user_id'];
}
