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

class HomeTransformer extends Fractal\TransformerAbstract
{

    protected $defaultIncludes = [

        'my_plans',
        'user_companies'
    ];

    private $params = [];
    function __construct($params = []) 
    {
        $this->params = $params;
        $this->params['user_companies']=0;
        $this->params['my_plans']=0;
        // $this->params['plans']=0;
        
    }


    public function transform(User $user)
    {


        $number = null;
        $number = $user->User_Companies;
        $this->params['user_companies']=count($number);


        $totalplans = null;
        $totalplans = $user->My_Plans;
        $this->params['my_plans']=count($totalplans);


        $accepted = null;
        $accepted = $user->My_Plans;
        $this->params['acceptedplans']=count($accepted);




        // $averagerating=null;
        // $averagerating=$

        return [
            "id" => (integer)$user->user_id ?: 0,
            "name" => (string) $user->user_name?: null,
            "companies" => (integer)$this->params['user_companies'] ? :0,
            "my_plans" => (integer)$this->params['my_plans'] ? :0,
            "accepted" => (integer)$user->my_plans->status ? :0,

            // "total accepted" => (integer)$this->params['my_plans'] ? :0,
            // // "total plans" => (string)$this->params['plans'] ? :0,
            
        ];

    }   

    // public function includeplans(User $user) {
    //     $plans = $user->Plans;

    //     return $this->collection($plans, new CompanyPlanTransformer);
    // }

    
}


