<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class InOrder implements ValidatorInterface {
	public function __construct(public int $flags = SORT_REGULAR){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_array($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be array for InOrder');
		}
		sort($value,$this->flags);
		return $value;
	}
}

?>