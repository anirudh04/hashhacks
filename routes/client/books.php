<?php

use App\Book;
use App\User_Book;
use App\Company;
use App\Reviews;
use App\Likes;
use Tuupola\Base62;
use Firebase\JWT\JWT;
use App\UserNotification;
use App\User;

use App\Book_Transformer;
use App\User_BookTransformer;
use App\Bank_DetailsTransformer;
use App\User_RegistrationNotification;
use App\Discussion_Answers;
use App\Discussion_Questions;
use App\Discussion_AnswersTransformer;
use App\Discussion_QuestionsTransformer;
use App\User_CompaniesTransformer;
use App\ReviewsTransformer;
use App\PlanTransformer;
use App\User_DetailTransformer;
use App\My_Plans;
use App\My_PlansTransformer;
use App\SinglePlanTransformer;
use App\HomeTransformer;

use Exception\NotFoundException;

use Response\NotFoundResponse;
use Response\ForbiddenResponse;
use Response\PreconditionFailedResponse;
use Response\PreconditionRequiredResponse;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;

$app->get("/books", function ($request, $response, $arguments) 
{	
// 	$token = $request->getHeader('Authorization');
// 	$decoded_token = substr($token[0], strpos($token[0], " ") + 1); 
// 	$JWT = $this->get('JwtAuthentication');
// 	$decoded_token = $JWT->decodeToken($JWT->fetchToken($request));	$id =$decoded_token->id;
	$books = $this->spot->mapper("App\Book")->query("SELECT * FROM book");

	$fractal = new Manager();
	$fractal->setSerializer(new DataArraySerializer);

	$resource = new Collection($books, new Book_Transformer);
	$data = $fractal->createData($resource)->toArray();

	return $response->withStatus(200)
	->withHeader("Content-Type", "application/json")
	->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});


$app->get("/books/{naam}", function ($request, $response, $arguments) 
{	

	$name = $arguments['naam'];

	$books = $this->spot->mapper("App\Book")->query("SELECT * FROM book WHERE book_name='$name'");

	$fractal = new Manager();
	$fractal->setSerializer(new DataArraySerializer);

	$resource = new Collection($books, new Book_Transformer);
	$data = $fractal->createData($resource)->toArray();

	return $response->withStatus(200)
	->withHeader("Content-Type", "application/json")
	->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});


$app->get("/user_book", function ($request, $response, $arguments) 
{

	// $token = $request->getHeader('Authorization');
	// $decoded_token = substr($token[0], strpos($token[0], " ") + 1); 
	// $JWT = $this->get('JwtAuthentication');
	// $decoded_token = $JWT->decodeToken($JWT->fetchToken($request));


	
	$user_book = $this->spot->mapper("App\User_Book")
	->query("SELECT user_book.book_id,book.book_name,book.book_author,user_book.status FROM user_book,book WHERE book.book_id=user_book.book_id AND user_book.roll_no=101553005");



	// ->query("SELECT my_plans.plan_id,plans.name,my_plans.status,companies.logo FROM my_plans,plans,companies WHERE my_plans.plan_id=plans.plan_id AND companies.company_id=plans.company_id AND my_plans.user_id=$id ");


	$fractal = new Manager();
	$fractal->setSerializer(new DataArraySerializer);
	$resource = new Collection($user_book, new User_BookTransformer);
	$data = $fractal->createData($resource)->toArray();

	return $response->withStatus(200)
	->withHeader("Content-Type", "application/json")
	->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

$app->post("/issue_book", function ($request, $response, $arguments) 
{


	$body = $request->getParsedBody();
	


	$user_book['roll_no'] = $body['roll_no'];
	$user_book['book_id'] = $body['book_id'];
	$user_book['tag_id'] = $body['tag_id'];

	
	$user_book['fine'] = 0;
	
	if ($check = $this->spot->mapper("App\User_Book")->first([
		"book_id"=>$body['book_id'],"tag_id"=>$body['tag_id'],"status"=>"Issued"
	]))
	{

		throw new NotFoundException("Already issued!", 404);
	}

	else{
		$user_book['status'] = "Issued";
		$newresponse = new User_Book($user_book);
		$mapper = $this->spot->mapper("App\User_Book");
		$id = $mapper->save($newresponse);

		if($id)
		{
			$fractal = new Manager();
			$fractal->setSerializer(new DataArraySerializer);
			$entity = $mapper->where(["user_id"=>$id]);
			$data["status"] = "ok";
			$data["message"] = "Book issued";
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







	

	
}


);



$app->post("/return_book", function ($request, $response, $arguments) 
{

 //    $token = $request->getHeader('Authorization');
	// $decoded_token = substr($token[0], strpos($token[0], " ") + 1); 
	// $JWT = $this->get('JwtAuthentication');
	// $decoded_token = $JWT->decodeToken($JWT->fetchToken($request));
  //    if (true === $user_book = $this->spot->mapper("App\User_Book")->first([
  //   "user_id" => 1
  // ]))



  //    {

	$id=1;

	$body = $request->getParsedBody();
	$user_book['roll_no'] = $body['roll_no'];
	$user_book['book_id'] = $body['book_id'];
	$user_book['tag_id'] = $body['tag_id'];



	if($user_book = $this->spot->mapper("App\User_Book")->first(["roll_no" => $body['roll_no'],"book_id"=>$body['book_id']]))

	{
		
		$user_book->tag_id = $body['tag_id'];
		$user_book->book_id = $body['book_id'];
		$user_book->status = "Returned";
		$this->spot->mapper("App\User_Book")->update($user_book);

		$data["status"] = "ok";
		$data["message"] = "Book returned";
		
		
	}

	else
	{   

		throw new NotFoundException("Already returned!", 404);
	} 

	return $response->withStatus(201)
	->withHeader("Content-Type", "application/json")
	->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
}
);











	// if ($id) {
	// 	$check = $this->spot->mapper("App\User")
	// 	->first(["google_id" => $body['google_id'],
	// 	        "email" =>  $body['email']]);


	// 	$now = new DateTime();
	// 	$future = new DateTime("now +30 days");
	// 	$server = $request->getServerParams();
	// // $jti = Base62::encode(random_bytes(16));
	// 	$payload = [
	// 		"iat" => $now->getTimeStamp(),
	// 		"exp" => $future->getTimeStamp(),
	// 	// "jti" => $jti,
	// 		"id" => $check->user_id,

	// 	];
	// 	$secret = getenv("JWT_SECRET");
	// 	$token = JWT::encode($payload, $secret, "HS256");


/* Serialize the response data. */


	// $entity = $mapper->where(["user_id"=>$id]);



	// $resource = new Collection($entity, new User_BookTransformer());
	// $data["response"] = $fractal->createData($resource)->toArray()['data'][0];


		// 


