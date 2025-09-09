<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Common\Operators;

use Tak\Attributes\Common\To;

use Tak\Attributes\Common\Hash;

use Tak\Attributes\Common\Pattern;

use Tak\Attributes\Common\Func;

use Tak\Attributes\Return\Result;

function callMe(string $name) : string {
	return 'Mr.'.$name;
}

final class UserService {
	use AttributesEngine;

	#[Result(new Pattern(regex : '~hash\s:\s(?<hash>.+)~i',matches : 'hash'))]
	protected function register(
		#[Operators(18,'<='),Operators(25,'>')] int $age,
		#[Operators(0,'not'),Operators(1,'<<')] int $flag,
		#[Func(callable : 'callMe',enableCheck : false)] string $name,
		#[To('string'),Hash('sha256')] string $hash
	) : mixed {
		echo 'Hello ' , $name , PHP_EOL;
		return sprintf('age %d , flag : %d , hash : %s',$age,$flag,$hash);
	}
}

$service = new UserService();

var_dump($service->register(18,4,'TakNone',100101110101110));

?>