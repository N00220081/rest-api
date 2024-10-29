<!--  -->

<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('doctors', DoctorController::class);
});

Route::middleware('auth:sanctum')->group(function () {
        // using apiResource as its a convenient way to define a set of RESTful routes for a resource controller
        Route::apiResource('appointments', AppointmentController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', PatientController::class);
});