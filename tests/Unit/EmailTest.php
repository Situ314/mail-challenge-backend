<?php

namespace Tests\Unit;

use App\Http\Controllers\EmailController;
use App\Http\Requests\EmailRequest;
use App\Jobs\SendEmailJob;
use App\Mail\WoowupMailer;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Mockery\Mock;
use Mockery\MockInterface;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveEmailSuccessfully()
    {
        Queue::fake();

        // Mocking the EmailRequest data
        $requestData = [
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'recipient' => ['recipient1@example.com', 'recipient2@example.com'],
            'cc' => ['cc1@example.com', 'cc2@example.com'],
            'bcc' => ['bcc1@example.com', 'bcc2@example.com'],
        ];
        $emailRequest = new EmailRequest($requestData);

        $emailModel = \Mockery::mock(Email::class);
        $this->app->instance(Email::class, $emailModel);

        // Mocking the authenticated user ID
        $userId = 1;
        Auth::shouldReceive('id')->once()->andReturn($userId);

        $email_sent = (new EmailController())->send_email($emailRequest);

        // Assert Response
        $this->assertInstanceOf(JsonResponse::class, $email_sent);
        $this->assertEquals(200, $email_sent->getStatusCode());
        $this->assertEquals(['message' => 'Email queued'], json_decode($email_sent->getContent(), true));

        //Assert Queue was started
        Queue::assertPushed(SendEmailJob::class);

    }

    public function testSendEmailSuccessfully()
    {
        Mail::fake();

        $requestData = [
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'recipient' => 'recipient1@example.com;recipient2@example.com',
            'cc' => 'cc1@example.com;cc2@example.com',
            'bcc' => 'bcc1@example.com;bcc2@example.com',
            'user_id' => '1',
            'status' => 'queued',
            'comments' => 'sending',
        ];
        $emailRequest = new Email($requestData);

        //Dispatch Queue
        $sendEmailJob = new SendEmailJob($emailRequest);
        $sendEmailJob->handle();

        //Assert Mail sending
        Mail::assertSent(WoowupMailer::class);
    }
}
