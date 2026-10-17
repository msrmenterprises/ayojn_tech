<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Bid extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sponsor_for', 'sponsor_budget', 'sponsor_industry', 'sponsor_country', 'admin_status', 'status', 'bid_start_date', 'likeSponsorr', 'bid_end_date', 'country', 'contacted_by', 'identity', 'city','city_bidder_from'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function country_name()
    {
        return $this->belongsTo('App\Country', 'sponsor_country', 'country_code');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry', 'sponsor_industry');
    }

    public function bider()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function bidResponseWithSpam()
    {
        return $this->hasMany('App\ResponseBid', 'bid_id');
    }

    public function bidResponse()
    {
        return $this->hasMany('App\ResponseBid', 'bid_id')->where('is_spam', 0);
    }

    public function bidSpamResponse()
    {
        return $this->hasMany('App\ResponseBid', 'bid_id')->where('is_spam', 1);
    }

    public function bidSpecify()
    {
        return $this->hasMany('App\SponsorrSpecifyBid', 'bid_id');
    }

    public function city_name()
    {
        return $this->belongsTo('App\City', 'city', 'id');
    }

    //add by ram 23 sept
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
