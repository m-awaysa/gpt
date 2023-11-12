<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GPTController extends Controller
{

    public function getChatResponse(Request $request)
    {

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions',  [
                    'model' => 'gpt-3.5-turbo', // Make sure to use the correct model name
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an assistant who provides information on historical and current events.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $request->question
                        ]
                    ],
                ]);

            $answer = $response->json()['choices'][0]['message']['content'] ?? 'No answer provided.';
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getCode() === 0) {
                // Handle the timeout case
                return back()->with('error', 'The request to OpenAI timed out. Please try again.');
            }
        }

        return  view('welcome', [
            'answer' =>   $answer,
            'answer_on_context' => '',
        ]);
    }

    public function askGPT(Request $request)
    {
        $recipe = $request->input('context');
        $question = $request->input('question_for_context');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo', // Use the appropriate model
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.'
                ],
                [
                    'role' => 'user',
                    'content' => $recipe
                ],
                [
                    'role' => 'user',
                    'content' => $question
                ]
            ],
        ]);

        $answer = $response->json()['choices'][0]['message']['content'] ?? 'No answer provided.';

        return view('welcome', [
            'answer_on_context' => $answer,
            'answer' =>   '',
        ]);
    }


}
