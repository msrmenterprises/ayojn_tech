<?php

namespace App;

use App\Notifications\ControlpanelResetPassword;
use App\Notifications\UserResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    //use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_no', 'gender', 'company_name', 'address', 'about_me', 'profile_pic', 'organisation', 'sponsor_type', 'country', 'sponsor_for_specify', 'sponsor_for', 'sponsor_budget', 'sponsor_industry', 'sponsor_country', 'last_login_at', 'userstatus', 'entity', 'edit_count', 'disapprove_time', 'is_3refer', 'refer_datetime', 'likeSponsorr', 'identity', 'city', 'refer_code', 'is_bonus_used'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->attributes['role_id'] == 1) {
            $this->notify(new ControlpanelResetPassword($token));
        } else {
            $this->notify(new UserResetPassword($token));
        }

    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country', 'country_code');
    }

    public function Specify()
    {
        return $this->belongsTo('App\SponsorrSpecify', 'id', 'user_id');
    }

    public function counties()
    {
        return $this->belongsTo('App\Country', 'country', 'country_code');
    }

    public function sponsor_counties()
    {
        return $this->belongsTo('App\Country', 'sponsor_country', 'country_code');
    }


    public function country_name()
    {
        return $this->belongsTo('App\Country', 'country', 'country_code');
    }

    public function city_name()
    {
        return $this->belongsTo('App\City', 'city', 'id');
    }

    public function referBy()
    {
        return $this->belongsTo('App\User', 'refer_by', 'id');
    }

    public function country_sponsorr()
    {
        return $this->belongsTo('App\Country', 'sponsor_country', 'country_code');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry', 'sponsor_industry');
    }

    public function specification()
    {
        return $this->hasMany('App\SponsorrSpecify', 'id', 'user_id');
    }

    public function opportunity()
    {
        return $this->hasMany('App\Opportunity', 'id', 'receiver_id');
    }

    


}
