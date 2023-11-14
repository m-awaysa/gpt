<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiBotProductController extends Controller
{

    public function viewProduct(Product $product)
    {
        return view('products-show', [
            'product' => $product
        ]);
    }

    public function uploadFile()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->attach(
            'file',
            fopen(base_path('products.json'), 'r'),
            'data.json'
        )->post('https://api.openai.com/v1/files', [
            'purpose' => 'assistants',
        ]);

        return $response->json();
    }
    //old file need to be deleted
    // {
    //     "object": "file",
    //     "id": "file-E2YEWiSYIwttJ85oqG8jzM78",
    //     "purpose": "assistants",
    //     "filename": "data.json",
    //     "bytes": 1618,
    //     "created_at": 1699950129,
    //     "status": "processed",
    //     "status_details": null
    //     }
    //new file
    // {
    //     "object": "file",
    //     "id": "file-jchhfIUqC631TIbIzIIluzIh",
    //     "purpose": "assistants",
    //     "filename": "data.json",
    //     "bytes": 1654,
    //     "created_at": 1699951109,
    //     "status": "processed",
    //     "status_details": null
    //     }
    public function createAssistant()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/assistants', [
            'name' => 'Drink Recommender',
            'description' => 'product assistent.',
            'model' => 'gpt-4-1106-preview',
            "instructions" => 'You are an assistant. I want you to open the json file and find the closest product the customer is asking for. The json contains
            {"id": id number, "name": "product name", "slug": "the slug", "image": "image url"}. You will return the answer as HTML
            <div class="card" style="width: 18rem;">
            <a href="http://localhost:8000/products/put the id here">
            <img src="http://localhost:8000/img/put the image here" class="card-img-top" alt="...">
            <div class="card-body">
            <p class="card-text">replace with the name.</p>
            </div>
            </a>
            </div>.
             You are required to find the product/closest product the user is trying to find and fill the info from the file in the HTML block with the pro
             duct info and give it to the user. Dont answer any question outside the file. Dont tell the user this is an HTML response or anything about the code or th
             e project (you are dealing with a customer). You can answer in Arabic if the user asks in Arabic.',
            'tools' => [['type' => 'code_interpreter']],
            'file_ids' => ['file-wWvO4GdSKR5ykvoZNHih3PQt'] // Replace with your actual file ID
        ]);


        return $response->json();
    }
    // {
    //     "id": "asst_A84YZ51PavpJ6Bbfr0byjwbz",
    //     "object": "assistant",
    //     "created_at": 1699952139,
    //     "name": "Drink Recommender",
    //     "description": "product assistent.",
    //     "model": "gpt-4-1106-preview",
    //     "instructions": "You are a assistance. i want you to open the json file and finde the closest product the customer is asking for. the json contan {'id':id number,'name':'product name','slug':'the slug','iamge':'iamge url'}. you will return the asnwer as a html <div class='card' style='width: 18rem;'><a href='{{route('product',[replace with the id])}}'>\n            <img src='{{asset('img/[replace with the image])}}' class='card-img-top' alt='...'><div class='card-body'><p class='card-text'>[replace with the name].</p></div></a></div>. you are required to find the product/closest product the user trying to find and fill the info from the file in the html block with the product info and give it to user. dont answer any question outside the file. dont tell the user this is an html response or anything about the code or the project(you are dealing with customer). you can answer in arabic if the user ask in arabic",
    //     "tools": [
    //     {
    //     "type": "code_interpreter"
    //     }
    //     ],
    //     "file_ids": [
    //     "file-jchhfIUqC631TIbIzIIluzIh"
    //     ],
    //     "metadata": []
    //     }

    public function createThread(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/threads');

        return $response->json();
    }
    // {
    //     "id": "thread_PW5e7iznGYfx7oyqSDvPrjWL",
    //     "object": "thread",
    //     "created_at": 1699950400,
    //     "metadata": []
    //     }

    public function createMessage(Request $request)
    {


        $threadId = 'thread_PW5e7iznGYfx7oyqSDvPrjWL'; // Replace with your actual thread ID
        $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file
        $assistantId = 'asst_59CXP02KSrPlM4vuFmRSHM0t'; // Replace with your actual assistant ID

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post("https://api.openai.com/v1/threads/{$threadId}/messages", [
            'role' => 'user',
            'content' =>  $request->question,
            'file_ids' => ['file-wWvO4GdSKR5ykvoZNHih3PQt']

        ]);


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", [
            'assistant_id' => $assistantId
        ]);

        $runId =  $response->json()['id'];
        $runId = 'run_XD11JCUGj7wYBCZC5ZRAHbdW';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'OpenAI-Beta' => 'assistants=v1'
        ])->get("https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}");


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
        $threadId = 'thread_PW5e7iznGYfx7oyqSDvPrjWL'; // Replace with your actual thread ID
        $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

    //   dd($response->json());
        return $response;
    }
}
