<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Common\Json;

use Tak\Attributes\Common\Contains;

use Tak\Attributes\Common\EndsWith;

use Tak\Attributes\Common\StartsWith;

use Tak\Attributes\Common\In;

use Tak\Attributes\Common\To;

use Tak\Attributes\Return\Iterate;

final class UserService {
	use AttributesEngine;

	#[Iterate(new To('string'))]
	protected function register(
		#[Json(enableCheck : false)] string $json,
		#[StartsWith('@'),Contains('User'),EndsWith('!')] string $username,
		#[In(['user','admin'])] string $role = 'user'
	) : Generator {
		yield 1000;
		yield 2000;
		yield 3000;
		return sprintf('registered %s (%s) details %s',$username,$role,$json);
	}
}

$service = new UserService();

$generator = $service->register(['user'=>'you'],'@My User Name !','admin');

foreach($generator as $stringNumber){
	var_dump($stringNumber);
}

var_dump($generator->getReturn());

?>