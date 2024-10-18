<!-- Handles authentication of registration and logins -->

<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;

// Sends a post request to create a new user in the database
Route::post('/register', [AuthController::class, 'register']);
// Sends a post request to /login, to authenticate the user and return an API tokin if successful
Route::post('/login', [AuthController::class, 'login']);

// Defines groups of routes protected by auth:santum middleware, only registered (authenticated) users will be able to acess the routes defined in the group
// logout is defined within the middleware group because it requires the user to be authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
});