<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Vouche extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer_id', 'opportunity_id', 'vouch_value', 'status', 'vouch_identity', 'vouch_city', 'vouch_country', 'vouch_contacted'
    ];

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity', 'opportunity_id', 'id')->withTrashed();
    }

    public function userDetail()
    {
        return $this->hasOne('App\User', 'id', 'offer_id');
    }

    public function VouchUser()
    {
        return $this->hasOne('App\VouchUser', 'vouches_id', 'id')->where("offer_id", Auth::user()->id);
    }

    public function vouchesResponseUser()
    {
        return $this->hasMany('App\VouchUser', 'vouches_id')->where('status', 2);
    }


}
