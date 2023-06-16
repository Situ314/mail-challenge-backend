<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Jobs\SendEmailJob;
use App\Mail\WoowupMailer;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function get_emails()
    {
        try {
            $emails = Email::check_emails(Auth::id());

            return response()->json($emails, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in EmaiLController.get_email'
            ], 400);
        }
    }


    public function send_email(EmailRequest $request)
    {
        try {
            $recipientEmailAddress = $request->recipient;

            //Create the Email Object
            $emailRegister = new Email([
                'subject' => $request->subject,
                'body' => $request->body,
                'recipient' => $recipientEmailAddress,
                'cc' => $request->cc ? implode(";", $request->cc) : null,
                'bcc' => $request->bcc ? implode(";", $request->bcc) : null,
                'user_id' => Auth::id(),
            ]);

            $emailRegister->save();

            //Dispatch the job in charge of sending the Email
            SendEmailJob::dispatch($emailRegister);

            return response()->json([ 'message' => 'Email queued'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in EmaiLController.send_email'
            ], 400);
        }
    }
}
