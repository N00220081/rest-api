<!--  -->

<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

// Sends a post request to create a new user in the database
Route::post('/register', [AuthController::class, 'register']);
// Sends a post request to /login, to authenticate the user and return an API tokin if successful
Route::post('/login', [AuthController::class, 'login']);

// Defines groups of routes protected by auth:santum middleware, only registered (authenticated) users will be able to acess the routes defined in the group
// logout is defined within the middleware group because it requires the user to be authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::apiResource('doctors', DoctorController::class);

// Only doctor can access this
Route::middleware('auth:sanctum')->group(function () {
    // using apiResource as its a convenient way to define a set of RESTful routes for a resource controller
    Route::apiResource('appointments', AppointmentController::class);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', PatientController::class);
});