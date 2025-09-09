<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Pattern implements ValidatorInterface {
	public function __construct(public string $regex,public bool | int | string $matches = false){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_string($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be a string to match pattern');
		}
		if(preg_match($this->regex,$value,$matches) === false){
			throw new InvalidArgumentException('$'.$name.' does not match pattern '.$this->regex.' , given '.var_export($value,true));
		}
		return $this->matches !== false ? ($this->matches !== true ? $matches[$this->matches] : $matches) : $value;
	}
}

?>