<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorrSpecify extends Model
{
    use SoftDeletes;
    protected $fillable=['specify_name','user_id'];
}
