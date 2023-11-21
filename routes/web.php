<?php

use App\Http\Controllers\AiBotController;
use App\Http\Controllers\AiBotProductController;
use App\Http\Controllers\GPTController;
use App\Http\Controllers\GPTEmbiddingController;
use App\Http\Controllers\ImageGenerationController;
use App\Http\Controllers\ImageQueryController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TextToSpeechController;
use Illuminate\Support\Facades\Route;


use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Illuminate\Support\Facades\Http;
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

// turn speech to text
Route::get('/speech-to-text', function () {
    return view('speech-text');
})->name('speech.text');
Route::post('/convert-speech-to-text', [TextToSpeechController::class, 'convertToText'])->name('speech-to-text');



// ai bot assestance
Route::get('/chat-bot', function () {
    return view('chat-bot');
})->name('chat.bot');
Route::get('/uploadFile-chat-bot', [AiBotController::class, 'uploadFile'])->name('bot.init');
Route::get('/uploadFile-chat-bot/assi', [AiBotController::class, 'createAssistant'])->name('bot.init.assi');
Route::get('/create/thread', [AiBotController::class, 'createThread'])->name('create.thread');
Route::post('/create/message', [AiBotController::class, 'createMessage'])->name('create.message');
Route::get('/messages', [AiBotController::class, 'getMessages'])->name('get.message');



//ai bot assestance
Route::get('/product-bot', function () {
    return view('product-bot');
})->name('product.bot');
Route::get('/uploadFile-product-bot', [AiBotProductController::class, 'uploadFile'])->name('product.bot.init');
Route::get('/uploadFile-product-bot/assi', [AiBotProductController::class, 'createAssistant'])->name('product.bot.init.assi');
Route::get('/product/create/thread', [AiBotProductController::class, 'createThread'])->name('product.create.thread');
Route::post('/product/create/message', [AiBotProductController::class, 'createMessage'])->name('product.create.message');
Route::get('/product/messages', [AiBotProductController::class, 'getMessages'])->name('product.get.message');
//local route
Route::get('/products/{product}', [AiBotProductController::class, 'viewProduct'])->name('product');


//qr code
Route::post('/send/message/{uniqueId}', [QrCodeController::class, 'sendMessageToMainScreen'])->name('message');
Route::get('/QrCode', [QrCodeController::class, 'index'])->name('qrcode');
Route::get('/chat-with-assistant/{qrcode}', [QrCodeController::class, 'chatWithAssistant'])->name('chat-with-assistant');
Route::post('/stream/create/message', [QrCodeController::class, 'createMessage'])->name('stream.create.message');
Route::get('/stream/messages', [QrCodeController::class, 'getMessages'])->name('stream.get.message');
Route::get('/stream/end/answer', [QrCodeController::class, 'answerFinished'])->name('stream.end.answer');

// Route::get('/run-id', function () {


//     $threadId  = QrCode::where('code',  'bhWHs6XVSd')?->first()?->thread_id;

//     $apiKey = env('OPENAI_API_KEY'); // Ensure your API key is stored in the .env file
// ;

//     $response = Http::withHeaders([
//         'Authorization' => 'Bearer ' . $apiKey,
//         'OpenAI-Beta' => 'assistants=v1'
//     ])->get("https://api.openai.com/v1/threads/{$threadId}/runs/run_YWuTlsDqRmT0nFe94U0UwiN4");

//     return  $response->json();
// });
//login
Route::get('/login', [loginController::class, 'login'])->name('login');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');
// Route::get('/stream', [loginController::class, 'stream'])->name('stream');
