<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\ResendEmailRequest;
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
            //This will get the emails and clean some queued emails
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
            //Create the Email Object
            $emailRegister = new Email([
                'subject' => $request->subject,
                'body' => $request->body,
                'recipient' => implode(";", $request->recipient),
                'cc' => $request->cc ? implode(";", $request->cc) : null,
                'bcc' => $request->bcc ? implode(";", $request->bcc) : null,
                'user_id' => Auth::id(),
            ]);

            $emailRegister->save();

            //Since sending email messages can negatively impact the response time of the application
            // We are sending the email troug a Job, using the database as queue backend
            SendEmailJob::dispatch($emailRegister);

            return response()->json([ 'message' => 'Email queued'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in EmaiLController.send_email'
            ], 400);
        }
    }

    public function resend_email(ResendEmailRequest $request)
    {
        try {
            $emailRegister = Email::find($request->email_id);
            $emailRegister->status = 'queued';
            $emailRegister->update();

            if($emailRegister)
                //Dispatch the job in charge of sending the Email
                SendEmailJob::dispatch($emailRegister);
            else
                return response()->json([
                    'error' => 'Could not find record',
                    'message' => 'There is not an email with that id'
                ], 400);

            return response()->json([ 'message' => 'Resend Email queued'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in EmaiLController.resend_email'
            ], 400);
        }
    }
}
