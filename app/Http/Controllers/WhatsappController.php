<?php

namespace App\Http\Controllers;

use App\Models\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{


    public function index($number, $request)
    {

        $accessToken = env('ACCESS_TOKEN');
        $BsnsAccId = env('WhatsApp_Business_Account_ID');
        $phoneId = (int)env('Phone_number_ID');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer EAAYnvtfy1CQBO0xPZB0VbhHNlTmvY5UNmoWh85js7NLITXFAoehvb1BhkZAqsMtIG7p7O2nFZA2DN5ErAivb2VCR2IRiZBWrtAb4ZC0qwJzr9IKPxTE90mgjclI8taAjrTCzJQCvp3rDHtU8ZAkxuGBwyz4TeUxLtmhTWEXuI9WSIGzKEFsrX5V5uIFtby3Y6irzwNE4sgZB97azkfLt0WflCV6KbW6FcQJeUQZD',
            'Content-Type' => 'application/json',
        ])->post("https://graph.facebook.com/v17.0/192934250567181/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $number, // Add your 'to' value here
            "recipient_type" => "individual",
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "احكي معي مشان الله"
            ]
        ]);

        Whatsapp::create([
            'request' => strval($request),
            'response_after_send' => strval($response->body()),
        ]);
        // Access the response as needed
        return 'ok';
    }


    public function handleWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Check if mode and token were sent
        if ($mode && $token) {
            // Check if mode and token sent are correct
            if ($mode == 'subscribe' && $token == '53g5rge5e4') {
                // Respond with 200 OK and challenge token from the request
                Log::info('mode:' . $mode);
                Log::info('token:' . $token);
                Log::info('challenge:' . $challenge);
                return response($request->query('hub_challenge'), 200);
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
        Log::info('1: ' . $request);


        // Check if entry and changes exist before trying to access them
        if (isset($request->entry[0])) {
            // Access the message data
            $messageData = $request->input('entry')[0]['changes'][0]['value']['messages'][0]['from'];
            Log::info('3: ' . $messageData);
            $this->index($messageData, $request);
        }
        return response()->json(['message' => 'Webhook handled successfully'], 200);
    }
}
