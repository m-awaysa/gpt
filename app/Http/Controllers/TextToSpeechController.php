<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class TextToSpeechController extends Controller
{
    public function convertToSpeech(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/audio/speech', [
            'model' => 'tts-1',
            'input' => $request->input_text,
            'voice' => $request->voice,
        ]);

        if ($response->successful()) {
            // Assuming the API returns a URL or binary content of the audio file
            $audioContent = $response->body();

            // Save the audio content to a file in storage
            $fileName = 'speech.mp3';
            Storage::disk('local')->put($fileName, $audioContent);

            // Provide a link to download the MP3 file
            return response()->download(storage_path('app') . '/' . $fileName);
        }

        // Handle errors here
        return back()->withErrors('Unable to convert text to speech.');
    }
}
