<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'user_id',
        'status',
        'category',
        'subcategory',
        'priority',
        'attachment'
    ];
    public function comments() {
        return $this->hasMany(TicketComment::class);
    }
}