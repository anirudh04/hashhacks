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

use App\Book;
use League\Fractal;

class Book_Transformer extends Fractal\TransformerAbstract
{ 

    public function transform(Book $book)
    {

        return [
            "book_id" => (integer)$book->book_id ?: 0,
            "book_name" => (string)$book->book_name ?: null,
            "book_author" => (string)$book->book_author ?: null,
            "tag_id" => (integer)$book->tag_id ?: 0,
            "availability" => (string)$book->availability ?: null,
            "shelf_no" => (integer)$book->shelf_no ?: 0
        ];
    }
}
