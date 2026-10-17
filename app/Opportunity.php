<?php

namespace App;

use App\Notifications\ControlpanelResetPassword;
use App\Notifications\UserResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receiver_id', 'hashtag', 'country_code', 'industry_id','opportunity_city','is_reserved','reserved_year'
    ];

    public function country_name()
    {
        return $this->belongsTo('App\Country','country_code','country_code');
    }


    public function city_name()
    {
        return $this->belongsTo('App\City', 'opportunity_city', 'id');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry','industry_id');
    }

    public function opportunityUser()
    {
        return $this->belongsTo('App\User','receiver_id');
    }

    public function vouchResponse()
    {
        return $this->hasOne('App\Vouche','opportunity_id');
    }

    public function vouchesResponse()
    {
        return $this->hasMany('App\Vouche','opportunity_id');
    }


    public function OpportunityMapping()
    {
        return $this->hasOne('App\OpportunityUser','opportunity_id');
    }

    public function opportunityUsers()
    {
        return $this->belongsToMany('App\User','opportunity_users');
    }


}
