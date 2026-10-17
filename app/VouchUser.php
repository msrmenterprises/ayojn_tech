<?php

namespace App;

use App\Notifications\ControlpanelResetPassword;
use App\Notifications\UserResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class VouchUser extends Authenticatable
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer_id', 'vouches_id', 'status'
    ];


    public function opportunityUser()
    {
        return $this->belongsTo('App\User','offer_id');
    }

}
