<?php

use App\Http\Controllers\AiAssistantController;

Route::post('/ai/assistant', [AiAssistantController::class, 'handle']);
Route::post('/ai/report', [AiAssistantController::class, 'report']);
