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

    public function handleWebhook(Request $request)
    {
        $mode = $request->query('hub.mode');
        $token = $request->input('hub.verify_token');
        $challenge = $request->query('hub.challenge');

        foreach ($request->query() as $key => $value) {
            Log::info("$key  -: $value");
        }
        // Log::info('mode: ' . urldecode($mode));
        // Log::info('token: ' . $token);
        // Log::info('challenge: ' . $challenge);
        // Log::info('request: ' . $request);
        return response()->json(['challenge' => $challenge], 200);
        // Check if mode and token were sent

    }

    public function receive(Request $request)
    {
        dd($request->all());
    }
}
