<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {

   // return (int) $user->id === (int) $id;
    return true;
});


Broadcast::channel('hello-world', function () {
    // Allow ALL public channels (very useful in development!)
    return true;
});
