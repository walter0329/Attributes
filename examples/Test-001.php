<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Property\Set;

use Tak\Attributes\Property\Get;

use Tak\Attributes\Invoke\Async;

use Tak\Attributes\Invoke\Cache;

use Tak\Attributes\Invoke\IgnoreErrors;

use Tak\Attributes\Common\Pattern;

use Tak\Attributes\Common\Range;

use Tak\Attributes\Common\In;

use Tak\Attributes\Common\BindAs;

class Role {
	public function __construct(public string $role){
	}
}

final class UserService {
	#[Set(new In(['user','admin']))]
	#[Get(new BindAs('Role'))]
	protected string $role;

	use AttributesEngine;

	#[IgnoreErrors]
	#[Async(await : true)]
	#[Cache(ttl : 10)]
	protected function register(
		#[Range(18,99)] int $age,
		#[Pattern('/^[A-Za-z0-9_]{5,10}$/')] ? string $username = null,
		#[In(['user','admin'])] string $role = 'user'
	) : mixed {
		return sprintf('registered %s (%d) as %s',$username,$age,$role);
	}
}

$service = new UserService();

$service->role = 'admin';

var_dump($service->register(20,'alice_01','user'));

var_dump($service->role);

?>