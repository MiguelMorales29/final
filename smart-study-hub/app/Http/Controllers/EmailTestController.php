<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class EmailTestController extends Controller
{
    /**
     * Test email functionality
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Test basic email sending
            Mail::raw('This is a test email from Smart Study Hub', function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Smart Study Hub - Email Test');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test password reset email
     */
    public function testPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset email sent successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send password reset email: ' . $status
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed: ' . $e->getMessage()
            ], 500);
        }
    }
}













