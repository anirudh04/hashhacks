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

use Spot\EntityInterface;
use Spot\MapperInterface;
use Spot\EventEmitter;
use Tuupola\Base62;
use Psr\Log\LogLevel;
use App\User;
use App\Book;

class User_Fine extends \Spot\Entity
{
    protected static $table = "user_fine";

    public static function fields()
    {
        return [
            "user_fine_id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "user_id" => ["type" => "integer","unsigned"=>true],
            "book_id" => ["type" => "integer","unsigned"=>true],
            "fine" => ["type" => "integer"]
        ];
      }

    //     public static function relations(Mapper $mapper, Entity $entity) {
    // return [
    //   'Companies' => $mapper->hasManyThrough($entity, 'App\Companies','App\User','company_id','user_id')
    //   // 'User' => $mapper->belongsTo($entity, 'App\User', 'user_id')
    //   // // 'User_Companies' => $mapper->hasMany($entity, 'App\User_Companies', 'company_id'),
    //   // 'My_Plans' => $mapper->hasMany($entity, 'App\My_Plans', 'company_id')
    // ];
    // } 
    

  }

    