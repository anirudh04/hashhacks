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

class User_BookTransformer extends Fractal\TransformerAbstract
{
          

    public function transform(User_Book $user_book)
    {
        return [

            "book_id" => (integer)$user_book->book_id ?: 0,
            "name" => (string)$user_book->book_name ?: null,
            "author" => (string)$user_book->book_author ?: null,
            "status" => (string)$user_book->status ?: null
            // "return_date" => (integer)$user_book->time_stamp ?: 0
        ];
    }
}
