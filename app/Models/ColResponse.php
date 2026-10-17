<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColResponse extends Model
{
    //
    protected $fillable = ['respond_user_id', 'col_id', 'description', 'portfolio_link', 'status'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'respond_user_id');
    }
}
