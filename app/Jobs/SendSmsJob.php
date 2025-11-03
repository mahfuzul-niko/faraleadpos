<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SentSMS;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SendSmsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $phoneNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $phoneNumber)
    {
        $this->message = $message;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SentSMS $smsSettings)
    {

                if (!empty($this->phoneNumber)) {
                    try {
                        //Log::info('Sending SMS started.');
                        $send_sms = $smsSettings->send_sms($this->message, $this->phoneNumber);

                        $error_values = ['1002', '1003', '1004', '1005', '1006', '1007', '1008', '1009', '1010', '1011', '1012', '1013', '1014'];
                        if (!in_array($send_sms, $error_values)) {
                            /* Success */
                            $smsSettings->insert([
                                'user_id' => Auth::user()->id,
                                'phone' => $this->phoneNumber,
                                'sms' => $this->message,
                                'created_at' => Carbon::now()
                            ]);
                        }
                        //Log::info('SMS sent to ' . $this->phoneNumber);
                        //Log::info('Sending SMS completed.');
                    } catch (\Exception $e) {
                        /* Failed */
                        //Log::error('Error sending SMS: ' . $e->getMessage());
                        $send_sms = 1001;
                    }
                }


    }

}
