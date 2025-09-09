<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Range implements ValidatorInterface {
	public function __construct(public float | int $min,public float | int $max){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_numeric($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be numeric for Range');
		}
		if($value < $this->min || $value > $this->max){
			throw new InvalidArgumentException('$'.$name.' must be between '.$this->min.' and '.$this->max.' , given '.var_export($value,true));
		}
		return $value;
	}
}

?>