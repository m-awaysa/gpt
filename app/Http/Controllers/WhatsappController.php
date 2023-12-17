<?php

namespace App\Http\Controllers;

use App\Models\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{


    public function index($request, $number, $timestamp, $decodedMessage)
    {

        $accessToken = env('ACCESS_TOKEN');
        $BsnsAccId = env('WhatsApp_Business_Account_ID');
        $phoneId = (int)env('Phone_number_ID');

        $formattedBody = date('Y-m-d H:i:s', $timestamp) . ' - ' . $decodedMessage;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WHATSAPPTOKEN'),
            'Content-Type' => 'application/json',
        ])->post("https://graph.facebook.com/v17.0/192934250567181/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $number,
            "recipient_type" => "individual",
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => $formattedBody,
            ]
        ]);

        Whatsapp::create([
            'request' => strval($request),
            'response_after_send' => strval($response->body()),
        ]);
        Log::info('5: ' . $response->body());
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
            $changes = $request->input('entry')[0]['changes'][0]['value'];

            // Extract timestamp and decoded Arabic message
            $timestamp = $changes['messages'][0]['timestamp'];
            $decodedMessage = json_decode('"' . $changes['messages'][0]['text']['body'] . '"');

            // Call the index method with the extracted data
            $this->index($request, $changes['contacts'][0]['wa_id'], $timestamp, $decodedMessage);
        }

        return response()->json(['message' => 'Webhook handled successfully'], 200);
    }
}
