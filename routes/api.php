<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccediController;

// =================== ROTTE PUBBLICHE ===================
Route::post('/register/{utente}', [RegisterController::class, 'register']);
Route::get('/accedi/{utente}/{hash?}', [AccediController::class, 'show']);
Route::get('/searchMail/{utente}', [AccediController::class, 'searchMail']);

Route::middleware(['autenticazione'])->group(function () {
    Route::get('me', [ProfileController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // ------------------- USER, e ADMIN: possono vedere i contenuti -------------------
    Route::get('movies', [MovieController::class, 'index']);
    Route::get('movies/{id}', [MovieController::class, 'show']);

    Route::get('series', [SeriesController::class, 'index']);
    Route::get('series/{id}', [SeriesController::class, 'show']);

    Route::get('episodes', [EpisodeController::class, 'index']);
    Route::get('episodes/{id}', [EpisodeController::class, 'show']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    // L'utente può modificare SOLO i suoi dati
    Route::put('me/update', [AuthController::class, 'updateProfile']);
    Route::post('me/add-credits', [ProfileController::class, 'addCredits']);

    // ------------------- SOLO EDITORE: gestione contenuti -------------------
    Route::middleware('role:admin')->group(function () {
        // Movies
        Route::post('movies', [MovieController::class, 'store']);
        Route::put('movies/{id}', [MovieController::class, 'update']);
        Route::delete('movies/{id}', [MovieController::class, 'destroy']);

        // Series
        Route::post('series', [SeriesController::class, 'store']);
        Route::put('series/{id}', [SeriesController::class, 'update']);
        Route::delete('series/{id}', [SeriesController::class, 'destroy']);

        // Episodes
        Route::post('episodes', [EpisodeController::class, 'store']);
        Route::put('episodes/{id}', [EpisodeController::class, 'update']);
        Route::delete('episodes/{id}', [EpisodeController::class, 'destroy']);

        // Categories
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Gestione utenti
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
    });
});