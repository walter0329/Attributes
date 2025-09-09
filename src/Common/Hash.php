<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Hash implements ValidatorInterface {
	public function __construct(public string $algo,public bool $binary = false,public array $options = array()){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_string($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be string for Hash');
		}
		return hash($this->algo,$value,$this->binary,$this->options);
	}
}

?>