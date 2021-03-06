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

use Spot\EntityInterface as Entity;
use Spot\EventEmitter;
use Spot\MapperInterface as Mapper;
use Tuupola\Base62;

class Book extends \Spot\Entity
{
  protected static $table = "book";

  public static function fields()
  {
    return [
      "book_id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
      "book_name" => ["type" => "string", "unsigned" => true], 
      "book_author" => ["type" => "string"],
      "tag_id" => ["type" => "integer"],
      "availability" => ["type" => "string"],
      "shelf_no" => ["type" => "integer"]
    ];
  }

  public static function relations(Mapper $mapper, Entity $entity) {
    return [
      'User_Book' => $mapper->hasMany($entity, 'App\User_Book', 'book_id')

      // 'My_Plans' => $mapper->hasMany($entity, 'App\My_Plans', 'company_id')
    ];
  }
}