<?php

namespace App\Jobs;

use App\Mail\WoowupMailer;
use App\Models\Email;
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
            $envelope = new WoowupMailer($this->email);

            Mail::to($this->email->recipient)
                ->cc($this->email->cc)
                ->bcc($this->email->bcc)
                ->send($envelope);

            $this->email->mailer = 'saved';
            $this->email->update();
        } catch (\Exception $e) {
            Log::error($e, $this->email);
            $this->email->mailer = 'failed';
            $this->email->update();
        }

    }
}
