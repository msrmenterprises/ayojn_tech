<?php

namespace App;

use App\Notifications\ControlpanelResetPassword;
use App\Notifications\UserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponseBid extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    //use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bid_id', 'respond_user_id', 'description', 'portfolio_link', 'is_accepted'];

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
    public function bid()
    {
        return $this->belongsTo('App\Bid', 'bid_id', 'id')->withTrashed();
    }

//
    public function userDetail()
    {
        return $this->hasOne('App\User', 'id', 'respond_user_id');
    }

}
