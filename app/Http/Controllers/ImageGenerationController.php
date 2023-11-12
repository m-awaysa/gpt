<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ImageGenerationController extends Controller
{
    public function generate(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/images/generations', [
            'prompt' => $request->prompt,
            'n' => 2,
            'size' => '1024x1024',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Do something with the response, like storing image data or URLs
            // For the sake of this example, we'll just return the raw data
            return view('image', ['images' => $data['data']]);
        }

        // Handle errors here
        return back()->withErrors('Unable to generate images.');
    }
}
