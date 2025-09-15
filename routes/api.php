<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\ChangeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Structure
Route::post('/structures', [StructureController::class, 'store']); //period_id , year_id
Route::get('/structures', [StructureController::class, 'index']);

//Change
Route::post('/changes', [ChangeController::class, 'change']);