<?php

namespace App\Jobs;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\PatientRegistered;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Patient $patient){}
    

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->patient->email)->send(new PatientRegistered($this->patient));
    }
}
