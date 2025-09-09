<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Common\Json;

use Tak\Attributes\Common\Contains;

use Tak\Attributes\Common\EndsWith;

use Tak\Attributes\Common\StartsWith;

use Tak\Attributes\Common\To;

final class UserService {
	use AttributesEngine;

	protected function register(
		#[Json] string $json,
		#[StartsWith('@'),Contains('User'),EndsWith('!')] string $username,
		#[To('string')] string $stringNumber
	) : mixed {
		return sprintf('registered %s (%s) details %s',$username,$stringNumber,$json);
	}
}

$service = new UserService();

var_dump($service->register(json_encode(['user'=>'me']),'@My User Name !',123456789));

?>