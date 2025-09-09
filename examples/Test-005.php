<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Invoke\Retry;

use Tak\Attributes\Invoke\TimeIt;

use Tak\Attributes\Invoke\IgnoreErrors;

use Tak\Attributes\Common\FilterVar;

use Tak\Attributes\Common\Vector;

use Tak\Attributes\Common\InOrder;

use Tak\Attributes\Common\Reverse;

use Tak\Attributes\Common\Is;

use Tak\Attributes\Common\To;

final class UserService {
	public int $counter = 0;

	use AttributesEngine;

	#[Retry(attempts : 5,delay : 0.2)]
	#[IgnoreErrors(log : false)]
	#[TimeIt]
	protected function register(
		#[FilterVar(FILTER_VALIDATE_URL,FILTER_FLAG_PATH_REQUIRED)] string $url,
		#[Vector(new Is('int')),Vector(new To('string'))] array $numbers,
		#[InOrder,Reverse] array $organized
	) : never {
		var_dump($organized);
		echo 'Counter : ' , ++$this->counter , PHP_EOL;
		throw new \Exception('I\'ll force you to try again and again !');
	}
}

$service = new UserService();

var_dump($service->register('https://example.com/withpath',array(1,2,3,4,5,6,7),array(5,2,7,4,1,0,3,6)));

?>