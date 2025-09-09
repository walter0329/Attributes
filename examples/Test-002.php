<?php

use Tak\Attributes\AttributesEngine;

use Tak\Attributes\Common\IP;

use Tak\Attributes\Common\FilterVar;

use Tak\Attributes\Common\BindAs;

use Tak\Attributes\Common\Range;

final class Age {
	public function __construct(public int $age){
		printf('Age %d'.PHP_EOL,$age);
	}
}

final class Ages {
	public function __construct(int $ageOne,int $ageTwo){
		printf('Ages %d && %d'.PHP_EOL,$ageOne,$ageTwo);
	}
}

final class UserService {
	use AttributesEngine;

	protected function register(
		#[Range(40,50),BindAs('Age')] Age $age,
		#[BindAs(class : 'Ages',spread : true)] Ages $ages,
		#[FilterVar(FILTER_VALIDATE_EMAIL)] string $email,
		#[IP(IP::BOTH)] string $ip,
		#[IP(IP::V4)] string $ipv4 = '127.0.0.1'
	) : mixed {
		return sprintf('registered %s (%d) with %s',$email,$age->age,$ip);
	}
}

$service = new UserService();

var_dump($service->register(age : 45,ages : array(96,69),email : 'mrtaknone@gmail.com',ip : '2001:4860:4860::8888'));

?>