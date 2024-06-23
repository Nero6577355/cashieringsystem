<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function showEmailForm()
    {
        return view('send-email');
    }

    // public function sendEmail(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'recipientEmail' => 'required|email',
    //         'emailSubject' => 'required|string',
    //         'emailContent' => 'required|string',
    //     ]);

    //     $toEmail = $validatedData['recipientEmail'];
    //     $subject = $validatedData['emailSubject'];
    //     $content = $validatedData['emailContent'];

    //     try {
    //         Mail::to($toEmail)->send(new DemoEmail($subject, $content));
    //         return response()->json(['success' => 'Email sent successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Error sending email'], 500);
    //     }
    // }
    public function sendEmail(Request $request)
{
    $validatedData = $request->validate([
        'recipientEmail' => 'required|email',
        'emailSubject' => 'required|string',
        'emailContent' => 'required|string',
    ]);

    $toEmail = $validatedData['recipientEmail'];
    $subject = $validatedData['emailSubject'];
    $content = $validatedData['emailContent'];
    $fromEmail = Auth::user()->email; // Get the logged-in user's email

    try {
        Mail::to($toEmail)->send(new DemoEmail($subject, $content, $fromEmail));
        return response()->json(['success' => 'Email sent successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error sending email'], 500);
    }
}

}
