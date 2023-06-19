<?php

namespace App\Models;

use Carbon\Carbon;
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
        'status',
        'comments',
        'user_id'
    ];

    protected $casts = [
        'created_at'  => 'date:d-m-Y H:i:s',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function check_emails($user_id){
        $emailsCheck = Email::where('user_id',$user_id)
                        ->get();

        $now = Carbon::now();

        $emails = $emailsCheck->map(function (Email $email) use ($now) {
            $email_date = Carbon::parse($email->updated_at)->addMinutes(15);
            if($email->status == 'queued' && $now->gt($email_date)){
                $email->status = 'failed';
                $email->comments = 'Email was not sent. We have an issue with both of our servers. Please try again.';
            }

            return $email;
        });

        return $emails;
    }
}
