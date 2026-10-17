<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorrSpecifyBid extends Model
{
    use SoftDeletes;
    protected $fillable=['specify_name','user_id','bid_id'];

     public function specifyName()
        {
            return $this->belongsTo('App\SponsorrSpecifyList','specify_name');
        }
}
