<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/ai-chat', function (Request $request) {
//     $prompt = $request->input('prompt');
//     $response = Http::post('http://localhost:11434/api/generate', [
//         'model' => 'llama3.2:1b',
//         'prompt' => $prompt,
//         'stream' => false
//     ]);
//     return $response->json();
// });


