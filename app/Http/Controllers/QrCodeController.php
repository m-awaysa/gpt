<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Illuminate\Support\Facades\Http;

class QrCodeController extends Controller
{
    public function index()
    {

        $uniqueId = Str::random(10);
        $model = QrCode::where('code', $uniqueId)?->first();
        while ($model !== null) {
            $uniqueId = Str::random(10);
            $model = QrCode::where('code', $uniqueId)?->first();
        }
        QrCode::create([
            'code' => $uniqueId,
            'period' => 2,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'thread_id' => 0

        ]);

        $url = route('chat-with-assistant', ['qrcode' => $uniqueId]);

        $qrCode = FacadesQrCode::size(200)->generate($url);
        return view('generate-qrcode', ['qrCode' => $qrCode, 'uniqueId' =>  $uniqueId]);
    }

    public function chatWithAssistant(Request $request)
    {
        $qrcode = $request->qrcode;
        $qrModel = QrCode::where('code', $qrcode)?->first();

        if (!$qrModel) {
            abort(403);
        }
        // Calculate the difference in days
        $daysDifference = Carbon::parse($qrModel->start_date)->diffInDays(Carbon::now());

        // Check if the difference is greater than the period
        if ($daysDifference > $qrModel->period) {
            abort(419);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/threads');

        if ($response->json()['id']) {
            $qrModel->thread_id = $response->json()['id'];
            $qrModel->save();
        }


        return view('chat', [
            'uniqueId' => $qrcode
        ]);
    }

    // MessageController.php
    public function sendMessageToMainScreen(Request $request, $uniqueId)
    {
        $message = $request->input('message');
        event(new MessageSent($message, $uniqueId));
        return response()->json(['status' => 'success']);
    }



    public function createMessage(Request $request)
    {
        $threadId  = QrCode::where('code',  $request->uniqueId)?->first()?->thread_id;

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

        return $response->json();
    }






    public function getMessages(Request $request)
    {
       // return  $request->uniqueId;
        $threadId  = QrCode::where('code',  $request->uniqueId)?->first()?->thread_id;

        $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

        return $response->json();
    }
}
