<?php

use App\Http\Controllers\AiBotController;
use App\Http\Controllers\GPTController;
use App\Http\Controllers\GPTEmbiddingController;
use App\Http\Controllers\ImageGenerationController;
use App\Http\Controllers\ImageQueryController;
use App\Http\Controllers\TextToSpeechController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// For a web route


//ask ai
Route::get('/', function () {
    return view('welcome');
})->name('ask.gpt');
Route::post('/openai-chat', [GPTController::class, 'getChatResponse'])->name('gpt');
Route::post('/ask-gpt', [GPTController::class, 'askGPT'])->name('ask-gpt');

// embidding ai
Route::get('/embidding', function () {
    return view('embidding');
})->name('embidding');
Route::get('/embed-recipes', [GPTEmbiddingController::class, 'embedRecipes'])->name('embed-recipes');
Route::post('/ask-gpt/embidding', [GPTEmbiddingController::class, 'askGPT'])->name('ask-gpt-embidding');


// generate iamge
Route::get('/image', function () {
    return view('image');
})->name('ask.image');
Route::post('/generate-image', [ImageGenerationController::class, 'generate'])->name('generate-image');


// ask about image
Route::get('/ask/image', function () {
    return view('ask-about-image');
})->name('ask.about.image');
Route::post('/submit-image-query', [ImageQueryController::class, 'submitQuery'])->name('image-query');




// turn text to speech
Route::get('/text-to-speech', function () {
    return view('text-speech');
})->name('text.speech');
Route::post('/convert-text-to-speech', [TextToSpeechController::class, 'convertToSpeech'])->name('text-to-speech');

// ai bot assestance
Route::get('/chat-bot', function () {
    return view('chat-bot');
})->name('chat.bot');

Route::get('/uploadFile-chat-bot', [AiBotController::class, 'uploadFile'])->name('bot.init');
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
Route::get('/uploadFile-chat-bot/assi', [AiBotController::class, 'createAssistant'])->name('bot.init.assi');
// {
//     "id": "asst_gtapXTPajkAHzebLL925UMSG",
//     "object": "assistant",
//     "created_at": 1699862690,
//     "name": "Drink Recommender",
//     "description": "This assistant is skilled in recommending drinks based on specific criteria. It can analyze a list of drinks with various attributes like temperature, type, sugar content, and additives. When provided with preferences such as \"a cold drink with apple and no sugar\", the assistant will intelligently suggest a drink from the list that matches these criteria.",
//     "model": "gpt-4-1106-preview",
//     "instructions": null,
//     "tools": [
//     {
//     "type": "code_interpreter"
//     }
//     ],
//     "file_ids": [
//     "file-vNe6mgHPbEaSrye1b5QqdXOH"
//     ],
//     "metadata": []
//     }

// Route::post('/i-want', [AiBotController::class, 'queryThread'])->name('i.want');

// Route::post('/i-wasnt/thres2', [AiBotController::class, 'getThreadMessages'])->name('i.wa2nt');
// // {
//     //     "object": "list",
//     //     "data": [
//     //     {
//     //     "id": "run_9XstSSqqVbGMeRmJ6ehiV5m0",
//     //     "object": "thread.run",
//     //     "created_at": 1699869312,
//     //     "assistant_id": "asst_gtapXTPajkAHzebLL925UMSG",
//     //     "thread_id": "thread_iqkH3i93sNzjBCP2Y3Mg5oaP",
//     //     "status": "completed",
//     //     "started_at": 1699869313,
//     //     "expires_at": null,
//     //     "cancelled_at": null,
//     //     "failed_at": null,
//     //     "completed_at": 1699869324,
//     //     "last_error": null,
//     //     "model": "gpt-4-1106-preview",
//     //     "instructions": null,
//     //     "tools": [
//     //     {
//     //     "type": "code_interpreter"
//     //     }
//     //     ],
//     //     "file_ids": [
//     //     "file-vNe6mgHPbEaSrye1b5QqdXOH"
//     //     ],
//     //     "metadata": []
//     //     },
//     //     {
//     //     "id": "run_uwRtblHdqOYyoFgEzmrQJqpd",
//     //     "object": "thread.run",
//     //     "created_at": 1699869239,
//     //     "assistant_id": "asst_gtapXTPajkAHzebLL925UMSG",
//     //     "thread_id": "thread_iqkH3i93sNzjBCP2Y3Mg5oaP",
//     //     "status": "completed",
//     //     "started_at": 1699869240,
//     //     "expires_at": null,
//     //     "cancelled_at": null,
//     //     "failed_at": null,
//     //     "completed_at": 1699869243,
//     //     "last_error": null,
//     //     "model": "gpt-4-1106-preview",
//     //     "instructions": null,
//     //     "tools": [
//     //     {
//     //     "type": "code_interpreter"
//     //     }
//     //     ],
//     //     "file_ids": [
//     //     "file-vNe6mgHPbEaSrye1b5QqdXOH"
//     //     ],
//     //     "metadata": []
//     //     }
//     //     ],
//     //     "first_id": "run_9XstSSqqVbGMeRmJ6ehiV5m0",
//     //     "last_id": "run_uwRtblHdqOYyoFgEzmrQJqpd",
//     //     "has_more": false
// //     }
// Route::post('/i-wasnt/thres23', [AiBotController::class, 'getThreadResponse'])->name('i.wa23nt');


Route::get('/create/thread', [AiBotController::class, 'createThread'])->name('create.thread');
//thread_M4uBti8G7hxPBekPxsc272Wk


Route::post('/create/message', [AiBotController::class, 'createMessage'])->name('create.message');


Route::get('/messages', [AiBotController::class, 'getMessages'])->name('get.message');


