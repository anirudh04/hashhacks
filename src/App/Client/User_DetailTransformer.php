<?php

/*
 * This file is part of the Slim API skeleton package
 *
 * Copyright (c) 2016-2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/slim-api-skeleton
 *
 */

namespace App;

use App\User;
use League\Fractal;

class User_DetailTransformer extends Fractal\TransformerAbstract
{

    public function transform(User $user)
    {
        return [

            "id" => (integer)$user->user_id ?: 0,
            "user_name" => (string)$user->user_name ?: null,
            "google_id" => (string)$user->google_id ?: null,
            "email" => (string)$user->user_email ?: null,
            "roll_no" => (integer)$user->roll_no ?: 0
        ];
    }
}
