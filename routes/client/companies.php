<?php

use App\Company;
use App\CompanyTransformer;
use App\SingleCompanyTransformer;


use Response\NotFoundResponse;
use Response\ForbiddenResponse;
use Response\PreconditionFailedResponse;
use Response\PreconditionRequiredResponse;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;

$app->get("/companies", function ($request, $response, $arguments) {

	$companies = $this->spot->mapper("App\Company")
        ->all();

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


$app->post("/company/{id}/rate", function ($request, $response, $arguments) {

	$data->message = "success";

	return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->post("/plan/{id}/like", function ($request, $response, $arguments) {

	$data->message = "success";

	return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->post("/plan/{id}/review", function ($request, $response, $arguments) {

	$data->message = "success";

	return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->post("/plan/{id}/discussion", function ($request, $response, $arguments) {

	$data->message = "success";

	return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});