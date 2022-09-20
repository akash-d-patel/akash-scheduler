<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\EmailSend;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailHistory;
    public $emailBody;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailHistory,$emailBody)
    {
        $this->emailHistory = $emailHistory;
        $this->emailBody = $emailBody;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $toEmailArray = explode(',', $this->emailHistory['to_email']);
        if($this->emailHistory['cc'] == null){
            $ccArray = null;
        } else {
            $ccArray = explode(',', $this->emailHistory['cc']);
        }
        if($this->emailHistory['bcc'] == null){
            $bccArray = null;
        } else {
            $bccArray = explode(',', $this->emailHistory['bcc']);
        }
       
        Mail::to($toEmailArray)
        ->cc($ccArray)
        ->bcc($bccArray)
        ->send(new EmailSend($this->emailHistory,$this->emailBody));
    }
}
