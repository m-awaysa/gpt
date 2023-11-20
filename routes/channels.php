<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('messenger', function ($user) {
//     return !is_null($user);
// });

// Broadcasting channel route, using the unique ID for the channel name
Broadcast::channel('chat.{uniqueId}', function ($user, $uniqueId) {
    // You can perform any necessary checks here
    // For example, check if the uniqueId is valid, and the user is allowed to access this channel
    return true;
});
