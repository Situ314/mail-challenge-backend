<?php

namespace App\Jobs;

use App\Mail\WoowupMailer;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Email $email
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{//Create Envelope
            $envelope = new WoowupMailer($this->email);

            //Check if CC users were send, if so, create an array of them, since Mail only accepts array for this field
            $ccUsers = $this->email->cc;
            if($ccUsers){
                $ccUsers = explode(';', $ccUsers);
            }

            //Check if BCC users were send, if so, create an array of them, since Mail only accepts array for this field
            $bccUsers = $this->email->bcc;

            if($bccUsers){
                $bccUsers = explode(';', $bccUsers);
            }

            /*
             * to() method is for passing the
             * receiver email address.
             *
             * the send() method to incloude the
             * WooupMailer class that contains the email template.
             */
            Mail::to($this->email->recipient)
                ->cc($ccUsers)
                ->bcc($bccUsers)
                ->send($envelope);

            //if nothing happens, save the Email object with a succeed message
            $this->email->status = 'sent';
            $this->email->comments = 'Sent at '.Carbon::now()->format('d-m-Y H:i:s');
            $this->email->update();
        } catch (\Exception $e) {
            //save the Email object with a failed status
            $this->email->status = 'failed';
            $this->email->comments = 'Something went wrong: '.$e->getMessage();
            $this->email->update();

            Log::error($e);
        }

    }
}
