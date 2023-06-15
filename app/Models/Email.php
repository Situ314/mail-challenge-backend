<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'recipient',
        'cc',
        'bcc',
        'mailer',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
