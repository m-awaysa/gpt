<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageQueryController extends Controller
{
    public function submitQuery(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-vision-preview',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $request->text_query,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $request->image_url,
                            ],
                        ],
                    ],
                ],
            ],
            'max_tokens' => 300,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Return the response or handle it as needed
            return view('ask-about-image', ['response' => $data]);
        }

        // Handle errors here
        return back()->withErrors('Unable to process the image query.');
    }
}
