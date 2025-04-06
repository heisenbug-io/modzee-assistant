<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiAssistantController;

Route::post('/ai/assistant', [AiAssistantController::class, 'handle']);
Route::post('/ai/reset', [AiAssistantController::class, 'reset']);
Route::get('/ai/logs', [AiAssistantController::class, 'logs']);
