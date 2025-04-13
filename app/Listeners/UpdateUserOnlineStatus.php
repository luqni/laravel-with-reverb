<?php

namespace App\Listeners;

use App\Events\UserOnlineEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class UpdateUserOnlineStatus
{
    public function handleLogin($event)
    {
        $user = $event->user;
        $user->is_online = true;
        $user->save();

        event(new UserOnlineEvent($user, true));
    }

    public function handleLogout($event)
    {
        $user = $event->user;
        $user->is_online = false;
        $user->save();

        event(new UserOnlineEvent($user, false));
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
        ];
    }
}