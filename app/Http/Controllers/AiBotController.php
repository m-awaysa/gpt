<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiBotController extends Controller
{

    public function uploadFile()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->attach(
            'file',
            fopen(base_path('data.json'), 'r'),
            'data.json'
        )->post('https://api.openai.com/v1/files', [
            'purpose' => 'assistants',
        ]);

        dd($response->json());
    }
    // array:8 [▼ // app\Http\Controllers\AiBotController.php:20
    //   "object" => "file"
    //   "id" => "file-vNe6mgHPbEaSrye1b5QqdXOH"
    //   "purpose" => "assistants"
    //   "filename" => "data.json"
    //   "bytes" => 2668
    //   "created_at" => 1699862176
    //   "status" => "processed"
    //   "status_details" => null
    // ]
    public function createAssistant()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/assistants', [
            'name' => 'Drink Recommender',
            'description' => 'This is an assistant for recommending drinks based on specific criteria.he take the customer request and return the id of the closeset 2 recipe from the file uploaded.',
            'model' => 'gpt-4-1106-preview',
            "instructions" => "You are a assistance. i provided a json file, each object is a drink,each object has a keyword about the drink like additives,temperature or type.There is no name key in the JSON objects.we can identify hot drinks using the temperature key and each key point to somthing. you will take the custoemr word/keyword and from that you will try to find the closest drink they are asking for or they feels like to drink. you will recommand a drink not searching for exact word. return the id for the closest match(you cant return 2 recipe or more if needed). and put in mind you are dealing with customer so try to recommand them the closest drink they are trying to find. dont answer any question outside the file. dont tell the user about the file structure(important). talk like you are talking to normal human. you can answer in arabic if the user ask in arabic",
            'tools' => [['type' => 'code_interpreter']],
            'file_ids' => ['file-vNe6mgHPbEaSrye1b5QqdXOH'] // Replace with your actual file ID
        ]);

        return $response->json();
    }

    public function createThread(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/threads');

        return $response->json();
    }


    public function createMessage(Request $request)
    {


        $threadId = 'thread_gw4lIIYGOq5v8YeLIriB6ILd'; // Replace with your actual thread ID
        $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file
        $assistantId = 'asst_Ow3NSIPDhFnhVBqg1OBYPKyU'; // Replace with your actual assistant ID

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post("https://api.openai.com/v1/threads/{$threadId}/messages", [
            'role' => 'user',
            'content' =>  $request->question,
            'file_ids' => ['file-vNe6mgHPbEaSrye1b5QqdXOH']

        ]);


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", [
            'assistant_id' => $assistantId
        ]);


        // $runId =  $response->json()['id'];
        // $runId = 'run_XD11JCUGj7wYBCZC5ZRAHbdW';

        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $apiKey,
        //     'OpenAI-Beta' => 'assistants=v1'
        // ])->get("https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}");



        // while ($response->json()['status'] !=  'completed') {


        //     $response = Http::withHeaders([
        //         'Authorization' => 'Bearer ' . $apiKey,
        //         'OpenAI-Beta' => 'assistants=v1'
        //     ])->get("https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}");
        // }



        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $apiKey,
        //     'Content-Type' => 'application/json',
        //     'OpenAI-Beta' => 'assistants=v1'
        // ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");


        return $response->json();
    }






    public function getMessages(Request $request)
    {

        $threadId = 'thread_gw4lIIYGOq5v8YeLIriB6ILd'; // Replace with your actual thread ID
        $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");


        return $response->json();
    }






    // public function queryThread(Request $request)
    // {

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
    //         'Content-Type' => 'application/json',
    //         'OpenAI-Beta' => 'assistants=v1'
    //     ])->post('https://api.openai.com/v1/threads', [
    //         'messages' => [
    //             [
    //                 'role' => 'user',
    //                 'content' => $request->question . ' note :' . 'Please recommend a drink based solely on the contents of the uploaded file. If an exact match for the request is not found in the file, suggest the closest available option. return just the id', // Replace with your query, // Replace with your query
    //                 'file_ids' => ['file-vNe6mgHPbEaSrye1b5QqdXOH'] // Replace with your actual file ID
    //             ]
    //         ]
    //     ]);


    //     return $this->getThreadMessages($response->json()['id'], $request->question);
    // }




    // public function getThreadMessages($thread_id = 'thread_fwrmOs4PY6TjUqFVFyBoLBGU', $question)
    // {
    //     $thread_id = 'thread_fwrmOs4PY6TjUqFVFyBoLBGU';
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
    //         'Content-Type' => 'application/json',
    //         'OpenAI-Beta' => 'assistants=v1'
    //     ])->post("https://api.openai.com/v1/threads/{$thread_id}/runs", [
    //         'assistant_id' => 'asst_StoGSNXt8M0V4yKzFutA0jLU',
    //     ]);

    //     return $this->getThreadResponse($thread_id, $question);
    // }



    // public function getThreadResponse($thread_id, $question)
    // {
    //     $thread_id = 'thread_fwrmOs4PY6TjUqFVFyBoLBGU';

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
    //         'OpenAI-Beta' => 'assistants=v1'
    //     ])->get("https://api.openai.com/v1/threads/{$thread_id}/messages", [
    //         'messages' => [

    //             [
    //                 'role' => 'system',
    //                 'content' => 'Please recommend a drink based solely on the contents of the uploaded file. If an exact match for the request is not found in the file, suggest the closest available option. return just the id'
    //             ],
    //             [
    //                 'role' => 'user',
    //                 'content' =>  $question . ' note :' . 'Please recommend a drink based solely on the contents of the uploaded file. If an exact match for the request is not found in the file, suggest the closest available option. return just the id', // Replace with your query
    //                 'file_ids' => ['file-vNe6mgHPbEaSrye1b5QqdXOH']
    //             ]
    //         ]
    //     ]);

    //     return view('chat-bot', [
    //         'response' =>  $response->json()['data']
    //     ]);
    // }

}
