<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['partner_id', 'qty', 'offer_for', 'identity', 'function', 'title', 'discount', 'deal_amount_delta', 'deal_amount_sigma', 'deal_amount_alpha', 'deal_amount_omega', 'incentive', 'available_in', 'currency', 'status', 'offerred_by', 'weblink', 'notification_email','refund','admin_status'];

    public function country()
    {
        return $this->belongsTo('App\Country', 'available_in', 'country_code');
    }

    public function partner()
    {
        return $this->belongsTo('App\User', 'partner_id', 'id');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry','function');
    }
}

