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

Broadcast::channel('private-chatroom.{roomId}', function ($user, $roomId) {
    // Verifikasi apakah user memiliki akses ke room tersebut
    // Bisa sesuaikan dengan peran tutor atau student, atau berdasarkan sesi pengguna
    return $user->id === session('id');  // Pastikan pengguna hanya bisa mengakses room yang sesuai dengan session mereka
});
    