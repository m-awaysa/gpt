<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function index()
    {

        $accessToken = env('ACCESS_TOKEN');
        $BsnsAccId = env('WhatsApp_Business_Account_ID');
        $phoneId = (int)env('Phone_number_ID');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://graph.facebook.com/v17.0/192934250567181/messages", [
            'messaging_product' => 'whatsapp',
            'to' => '972569207768', // Add your 'to' value here
            'type' => 'template',
            'template' => [
                'name' => 'hello_world',
                'language' => [
                    'code' => 'en_US',
                ],
            ],
        ]);
        // Access the response as needed
        dd($response->json());
    }

    // public function handleWebhook(Request $request)
    // {
    //     $mode = $request->query('hub_mode');
    //     $token = $request->query('hub_verify_token');
    //     $challenge = $request->query('hub_challenge');

    //     Log::info('mode: ' . $mode);
    //     Log::info('token: ' . $token);
    //     Log::info('challenge: ' . $challenge);
    //     Log::info('request: ' . $request);

    //     return response()->json(['challenge' => $challenge], 200);

    //     // Check if mode and token were sent

    // }


    public function handleWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Check if mode and token were sent
        if ($mode && $token) {
            // Check if mode and token sent are correct
            if ($mode == 'subscribe' && $token == 213123) {
                // Respond with 200 OK and challenge token from the request
                Log::info('WEBHOOK_VERIFIED');
                return response()->json(['challenge' => $challenge], 200);
            } else {
                // Respond with '403 Forbidden' if verify tokens do not match
                Log::info('VERIFICATION_FAILED');
                return response()->json(['status' => 'error', 'message' => 'Verification failed'], 403);
            }
        } else {
            // Respond with '400 Bad Request' if verify tokens do not match
            Log::info('MISSING_PARAMETER');
            return response()->json(['status' => 'error', 'message' => 'Missing parameters'], 400);
        }
    }


    public function receive(Request $request)
    {
        dd($request->all());
    }
}
