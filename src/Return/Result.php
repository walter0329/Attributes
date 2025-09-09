<?php

declare(strict_types = 1);

namespace Tak\Attributes\Return;

use Tak\Attributes\ReturnFilterInterface;

use Tak\Attributes\ValidatorInterface;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Result implements ReturnFilterInterface {
	public function __construct(protected ValidatorInterface $validator){
	}
	public function filter(string $name,mixed $value) : mixed {
		return $this->validator->validate('RETURN',$value);
	}
}

?>