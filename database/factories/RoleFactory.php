<?php

use App\User;

$factory->define(App\Role::class, function () {

    return [
        'user_id' => User::all()->first()->id,
        'role' => '*'
    ];
});
