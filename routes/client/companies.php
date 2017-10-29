<?php

use App\Company;
use App\CompanyTransformer;
use App\CompanyPlanTransformer;
use App\Company_Rating;
use App\SingleCompanyTransformer;


use Response\NotFoundResponse;
use Response\ForbiddenResponse;
use Response\PreconditionFailedResponse;
use Response\PreconditionRequiredResponse;
use Exception\NotFoundException;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;

$app->get("/companies", function ($request, $response, $arguments) {


  $token = $request->getHeader('Authorization');
  $decoded_token = substr($token[0], strpos($token[0], " ") + 1); 
  $JWT = $this->get('JwtAuthentication');
  $decoded_token = $JWT->decodeToken($JWT->fetchToken($request));



  $id=$decoded_token->id;
  $companies = $this->spot->mapper("App\Company")->query("SELECT companies.company_id,companies.logo,companies.rating,companies.name,companies.type FROM companies,user_companies WHERE user_companies.company_id=companies.company_id AND user_companies.user_id=$id");

  $fractal = new Manager();
  $fractal->setSerializer(new DataArraySerializer);

  $resource = new Collection($companies, new CompanyTransformer);
  $data = $fractal->createData($resource)->toArray();
  return $response->withStatus(200)
  ->withHeader("Content-Type", "application/json")
  ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});


$app->get("/company/{id}", function ($request, $response, $arguments) {

  if (false === $companies = $this->spot->mapper("App\Company")->first([
    "company_id" => $arguments["id"]
  ])) 
  {
    throw new NotFoundException(" Company not found.", 404);
  };

  if ($this->cache->isNotModified($request, $response)) {
    return $response->withStatus(304);
  }


  $fractal = new Manager();
  $fractal->setSerializer(new DataArraySerializer);
  $resource = new Item($companies, new SingleCompanyTransformer);
  $data = $fractal->createData($resource)->toArray();

  return $response->withStatus(200)
  ->withHeader("Content-Type", "application/json")
  ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});


$app->post("/ratecompany/{id}", function ($request, $response, $arguments) {


  $token = $request->getHeader('Authorization');
  $token = substr($token[0], strpos($token[0], " ") + 1); 
  $JWT = $this->get('JwtAuthentication');
  $token = $JWT->decodeToken($JWT->fetchToken($request));


  $body = $request->getParsedBody();
  $id_1=$arguments["id"];
  $companyrating['company_id'] =  $arguments["id"];
  $companyrating['user_id'] = $token->id;
  $companyrating['rating'] = $body['rating'];


  if ($check = $this->spot->mapper("App\Company_Rating")->first(["company_id" =>$id_1,"user_id"=>$token->id  
]))
  {
   throw new NotFoundException("Already Rated!", 404);
 }

 else
 {

   $newresponse = new Company_Rating($companyrating);
   $mapper = $this->spot->mapper("App\Company_Rating");
   $id = $mapper->save($newresponse);



   if ($id) {



    $rate=$this->spot->mapper("App\Company_Rating")->query("SELECT AVG(rating) AS abc FROM company_rating WHERE company_id=$id_1");


    $companies = $this->spot->mapper("App\Company")->first(["company_id" => $arguments["id"]]);
    $companies->rating=$rate[0]->abc;

    $this->spot->mapper("App\Company")->update($companies);
   


  



    /* Serialize the response data. */
    $fractal = new Manager();
    $fractal->setSerializer(new DataArraySerializer);

    $entity = $mapper->where(["rating_id"=>$id]);


    $data["status"] = "ok";
    // $data["id"] = $id;+
    $data["message"] = "Rated";


    return $response->withStatus(201)
    ->withHeader("Content-Type", "application/json")
    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
  } 
  else
  {

    $data["status"] = "error";
    $data["message"] = "Error in inserting!";

    return $response->withStatus(500)
    ->withHeader("Content-Type", "application/json")
    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
  }
}

});