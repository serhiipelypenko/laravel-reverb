<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Broadcast;

Route::get('/test-broadcast', function () {
    //event(new \App\Events\HelloWorld());
    //\App\Events\HelloWorld::dispatch();

    Broadcast::channel('hello-world')->broadcast('message.sent', [
        'id'      => 123,
        'user'    => 'John',
        'content' => 'Hello world!',
        'time'    => now()->toDateTimeString(),
    ]);
    return 'Event dispatched!';
});

Route::get('/', function () {
    \App\Events\HelloWorld::dispatch();
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Reverb Test Routes
Route::get('/reverb-test', function () {
    return Inertia::render('ReverbTest');
})->name('reverb-test');

Route::post('/reverb-test/send', function (Request $request) {
    $request->validate([
        'message' => 'required|string|max:1000',
        'sender' => 'required|string|max:50',
    ]);

    ChatMessage::dispatch(
        $request->message,
        $request->sender,
        now()->format('H:i:s')
    );

    return back();
})->name('reverb-test.send');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
