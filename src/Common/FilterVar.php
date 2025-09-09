<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class FilterVar implements ValidatorInterface {
	public function __construct(public int $filter = FILTER_DEFAULT,public array | int $options = FILTER_NULL_ON_FAILURE,public bool $return = false){
	}
	public function validate(string $name,mixed $value) : mixed {
		$result = filter_var($value,$this->filter,$this->options);
		if($result === false){
			throw new InvalidArgumentException('$'.$name.' filter a variable failed');
		}
		return $this->return ? $result : $value;
	}
}

?>