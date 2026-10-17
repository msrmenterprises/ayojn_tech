<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborate extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'collaborate_for',
        'sub',
        'remote_opportunity',
        'remote_country',
        'remote_city',
        'geo_focus',
        'industry_focus',
        'with_local_focus',
        'collaborate_with',
        'budget',
        'objective',
        'expiry_date',
        'status',
    ];

    public function specifyName()
    {
        return $this->belongsTo('App\SponsorrSpecifyList', 'sub');
    }

    public function country_name()
    {
        return $this->belongsTo('App\Country', 'geo_focus', 'country_code');
    }

    public function remote_country_name()
    {
        return $this->belongsTo('App\Country', 'remote_country', 'country_code');
    }

    public function remote_city_name()
    {
        return $this->belongsTo('App\City', 'remote_city', 'id');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry', 'industry_focus');
    }

    public function organizer()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function checkAttendes()
    {
        return $this->hasOne('App\Models\ColResponse', 'col_id', 'id')->where('respond_user_id', Auth::user()->id);
    }

    public function attendes()
    {
        return $this->hasMany('App\Models\ColResponse', 'col_id', 'id');
    }
}
