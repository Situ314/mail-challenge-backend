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
    public function send_email(EmailRequest $request)
    {
        try {
            /**
             * to() method is for passing the
             * receiver email address.
             *
             * the send() method to incloude the
             * WooupMailer class that contains the email template.
             */

            $recipientEmailAddress = $request->recipient;
            $userName = Auth::user()->name;
            $userMail = Auth::user()->email;

//            $envelope = new WoowupMailer($request->subject, $request->body);
//
//            Mail::mailer('mailgun')
//                ->to($recipientEmailAddress)
//                ->send($envelope);
            $emailRegister = new Email([
                'subject' => $request->subject,
                'body' => $request->body,
                'recipient' => $recipientEmailAddress,
                'cc' => $request->cc,
                'bcc' => $request->bcc,
                'mailer' => 'mailgun',
                'user_id' => Auth::id(),
            ]);

            $emailRegister->save();

            SendEmailJob::dispatch($emailRegister);
           // dispatch(new SendEmailJob());

            return response()->json([ 'message' => 'Email sent'], 200);

        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in EmaiLController.send_email'
            ], 400);
        }
    }
}
