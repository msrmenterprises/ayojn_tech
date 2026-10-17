<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VouchCode extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['vouch_code', 'vouch_amount','expeiry_date'];
}
