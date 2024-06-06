<?php

use App\Http\Controllers\api\AuthenticateController;
use App\Http\Controllers\api\AttendeeController;
use App\Http\Controllers\api\EventController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthenticateController::class, 'login']);
Route::post('/logout', [AuthenticateController::class, 'logout']);

Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)->except('update');
