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

class User extends \Spot\Entity
{
  protected static $table = "user";

  public static function fields()
  {
    return [
      "user_id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
      "user_name" => ["type" => "string"], 
      "google_id" => ["type" => "string"],   
      "user_email" => ["type" => "string"],   
      "roll_no" => ["type" => "integer"]
       ];
  }

  // public static function relations(Mapper $mapper, Entity $entity) {
  //   return [
      
  //     'User_Book' => $mapper->hasMany($entity,'App\User_Book','user_id')
  //          ];

  // }
}

   