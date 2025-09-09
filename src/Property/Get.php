<?php

declare(strict_types = 1);

namespace Tak\Attributes\Property;

use Tak\Attributes\PropertyInterface;

use Tak\Attributes\ValidatorInterface;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Get implements PropertyInterface {
	public function __construct(protected ValidatorInterface $validator){
	}
	public function check(string $name,mixed $value) : mixed {
		return $this->validator->validate($name,$value);
	}
}

?>