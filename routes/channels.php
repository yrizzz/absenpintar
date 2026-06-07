<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('dashboard.admin', function ($user) {
    return $user->hasAnyRole(['super_admin', 'hr_admin']);
});

Broadcast::channel('dashboard.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

