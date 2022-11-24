<?php

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


// Broadcast::channel('messages.{id}', function ($user, $id) {
//     return $user->id === (int) $id;
// });

Broadcast::channel('messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
    // return true;
    // return $user->id === (int) DB::table('messages_view')->where('receiverId', $id)->pluck('receiverId')->first();
});

