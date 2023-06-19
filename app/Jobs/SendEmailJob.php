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
        try{
            Log::info('Sent started '.$this->email->id);

            //array of recipients
            $recipientUsers = explode(';', $this->email->recipient);

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

            $comment = '';

            /*
             * Send the emails, but needs to be change for a loop
             * Since the 'to' method appends email addresses to the mailable's list of recipients, each iteration through the loop will send another email
             * to every previous recipient. Therefore, we need to re-create the mailable instance for each recipient:
             */
            foreach ($recipientUsers as $key => $recipient){
                Log::info('Sending to: '.$recipient);
                $emailSent = Mail::to($recipient);

                //hacky way to avoid sending multiple emails to the CC and BCC list
                //We are just attaching them to the last recipient
                if ($key === array_key_last($recipientUsers)) {
                    $emailSent
                        ->cc($ccUsers)
                        ->bcc($bccUsers);
                }

                //send the Email
                $emailSent = $emailSent->send(new WoowupMailer($this->email));

                //Check if it was successfull
                if($emailSent){
                    //get Message Id
                    $messageId = $emailSent->getMessageId();

                    //Save sent date
                    $sentAt = 'sent at '.Carbon::now()->format('d-m-Y H:i:s');

                    //Get who sent it. Hacky way, since Sendgrid messageId always start with <, mailgun uses a simple letter
                    $sentBy = 'by '.(mb_substr($messageId, 0, 1) == '<' ? 'Sendgrid ' : 'Mailgun ');

                    $comment .= '| Mail to: '.$recipient.' '.$sentAt.' '.$sentBy;
                    Log::info('Sending '.$this->email->id.' to: '.$recipient.' succeed and we are saving. '.$messageId);
                }else{
                    Log::info('Sending '.$this->email->id.' to: '.$recipient.' failed.');
                    $comment .= '| Mail to: '.$recipient.' failed, please check';
                }
            }
            Log::info('Sent finnish '.$this->email->id);

            //if nothing happens, at least one mail was succesfull so, we save the data
            $this->email->status = 'sent';
            $this->email->comments = $comment;
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
