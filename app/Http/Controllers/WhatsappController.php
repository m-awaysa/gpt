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
            'Authorization' => 'Bearer EAAYnvtfy1CQBOzUhNt7ZCYxHZBZAwKrqyB57ZCRKrIxYxsc4UlBAIggZBi5L6MwGiiEvZBIBNZCtH4uyFSUB0APkJTzf2DxhJfk7dZBrDMrRed74Lim11Jl5A4Ii38i4XdJkporf8OZCSWJHngmIsikZCWrXSv6WrfyI6g568hgQrqrZCgt7TBz9cmEXJCP1hAoIQIIrkurShP76ZBCv7cQlhA389eQuYhoWvB6A6YIZD',
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
        Log::info('$request->all()',$request->all());
        Log::info('2:'.$request->entry);
        
    }
}
