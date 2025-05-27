<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CommunicationController;
use Illuminate\Support\Facades\Route;


// Страницы общего доступа
Route::group([
    'controller' => IndexController::class,
    'as' => 'page.',
], function () {
    Route::get('/', 'home')->name('home');
    Route::get('/login', 'login')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::get('/support', 'support')->name('support');
    Route::get('/recovery', 'recovery')->name('recovery');
});


// Авторизация
Route::group([
    'controller' => AuthController::class,
    'as' => 'auth.',
    'prefix' => '/auth'
], function () {
    Route::post('/create', 'createUser')->name('createUser');
    Route::post('/login', 'loginUser')->name('loginUser');
    Route::post('/logout', 'logoutUser')->name('logoutUser');
    Route::post('/verify-2fa', 'verifyTwoFactor')->name('verify2fa');
});

// Страницы для авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::get('/communications', [CommunicationController::class, 'communications'])->name('communications');
    Route::post('/communications/create', [CommunicationController::class, 'createCommunication'])->name('createCommunication');
    Route::delete('/communications/delete', [CommunicationController::class, 'deleteCommunication'])->name('deleteCommunication');

});

Route::middleware('auth')->group(function () {
    Route::get('/connections', [ConnectionController::class, 'connections'])->name('connections');

    Route::post('/connections/email/create', [ConnectionController::class, 'createEmail'])->name('CreateEmail');
    Route::put('/connections/email/{id}', [ConnectionController::class, 'updateEmail'])->name('UpdateEmail');
    Route::delete('/connections/email/{id}', [ConnectionController::class, 'deleteEmail'])->name('DeleteEmail');

    Route::post('/connections/messenger/create', [ConnectionController::class, 'createMessenger'])->name('CreateMessenger');
    Route::put('/connections/messenger/{id}', [ConnectionController::class, 'updateMessenger'])->name('UpdateMessenger');
    Route::delete('/connections/messenger/{id}', [ConnectionController::class, 'deleteMessenger'])->name('DeleteMessenger');
});




Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/get-user-emails/{userId}', [AdminController::class, 'getUserEmails']);
});

