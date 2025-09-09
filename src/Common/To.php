<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class To implements ValidatorInterface {
	public function __construct(public string $type){
	}
	public function validate(string $name,mixed $value) : mixed {
		settype($value,$this->type);
		return $value;
	}
}

?>