<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class EndsWith implements ValidatorInterface {
	public function __construct(public string $needle){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_string($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be string for EndsWith');
		}
		if(str_ends_with($value,$this->needle) === false){
			throw new InvalidArgumentException('$'.$name.' must be ends with '.$this->needle.' , given '.var_export($value,true));
		}
		return $value;
	}
}

?>