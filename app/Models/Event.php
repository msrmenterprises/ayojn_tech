<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'event_title',
        'event_date',
        'industry_focus',
        'event_finish',
        'geo_focus',
        'timezone',
        'event_type',
        'event_location',
        'event_free_paid',
        'event_fee',
        'payment_link',
        'voucher_code',
        'status',
        'share_id'
    ];

    public function organizer()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function attendes()
    {
        return $this->hasMany('App\Models\Attendee', 'event_id', 'id');
    }

    public function checkAttendes()
    {
        return $this->hasOne('App\Models\Attendee', 'event_id', 'id')->where('user_id', Auth::user()->id);
    }

    public function country_name()
    {
        return $this->belongsTo('App\Country', 'geo_focus', 'country_code');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry', 'industry_focus');
    }


}
