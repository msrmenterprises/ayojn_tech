<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpportunityUser extends Model
{
    public $timestamps = false;
    protected $fillable = ['opportunity_id', 'user_id'];
}
