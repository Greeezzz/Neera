<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\FriendRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/custom', [ProfileController::class, 'updateProfile'])->name('profile.update.custom');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Public profile view
    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('user.profile');
    
    // Friends page
    Route::get('/friends', [FriendRequestController::class, 'index'])->name('friends.index');
    
    // Post detail page
    Route::get('/post/{id}', function($id) {
        return view('post-detail', compact('id'));
    })->name('post.detail');
    
    // Friendship routes
    Route::post('/friend/request/{user}', [FriendshipController::class, 'sendRequest'])->name('friend.request');
    Route::post('/friend/accept/{friendship}', [FriendshipController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/friend/reject/{friendship}', [FriendshipController::class, 'rejectRequest'])->name('friend.reject');
    Route::delete('/friend/remove/{user}', [FriendshipController::class, 'removeFriend'])->name('friend.remove');

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{user}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{user}/messages', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{user}/stream', [ChatController::class, 'stream'])->name('chat.stream');
    Route::patch('/chat/{user}/messages/{message}', [ChatController::class, 'updateMessage'])->name('chat.message.update');
    Route::delete('/chat/{user}/messages/{message}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');
});

require __DIR__.'/auth.php';
