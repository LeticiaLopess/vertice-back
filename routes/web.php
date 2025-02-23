<?php
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['api'])->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/isAuthenticated', [AuthenticatedSessionController::class, 'checkAuth']);
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
        Route::apiResource('subjects', SubjectController::class);
        Route::apiResource('topics', TopicController::class);
        Route::apiResource('questions', QuestionController::class);
        Route::get('subjects/{subject}/topics', [TopicController::class, 'getTopicsBySubject']);
        Route::get('topics/{topic}/children', [TopicController::class, 'getChildrenTopics']);
        Route::get('questions/grouped', [QuestionController::class, 'getGroupedQuestions']);
    });
});

Route::get('/isAuthenticated', [AuthController::class, 'isUserAuthenticated']);

require __DIR__.'/auth.php';
