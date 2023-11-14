<?php

use App\Http\Controllers\AiBotController;
use App\Http\Controllers\AiBotProductController;
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

