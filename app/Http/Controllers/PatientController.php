<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;
use App\Jobs\SendEmail;
use App\Notifications\PatientRegistered;
use Illuminate\Support\Facades\Notification;

class PatientController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:patients',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'document_img_url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient = Patient::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'document_img_url' => $request->document_img_url,
        ]);

        Notification::route('mail', $patient->email)->notify(new PatientRegistered($patient));
        //SendEmail::dispatch($patient);

        return response()->json(['patient' => $patient], 201);
    }
}