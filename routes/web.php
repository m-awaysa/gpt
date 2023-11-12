<?php

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
