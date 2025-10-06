<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SurveyController;

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


Route::get('/', function(){
    return response()->json(["status" => true, "message" => "Hello from e-SKM APIv1.0", 'date'    => date('d-F-Y'),'time'=>date('H:i:s')], 200);
});

Route::get('form/pendidikan-option', [SurveyController::class, 'PendidikanOption']);
Route::get('form/pekerjaan-option', [SurveyController::class, 'PekerjaanOption']);
Route::get('form/opd-option', [SurveyController::class, 'OpdOption']);
Route::get('form/layanan-opd-option', [SurveyController::class, 'layananOption']);
Route::get('form/survey-option', [SurveyController::class, 'surveyOption']);
Route::get('site-setting', [SurveyController::class, 'siteSetting']);
Route::get('survey/pertanyaan',[SurveyController::class, 'getPertanyaan']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
