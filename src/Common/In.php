<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class In implements ValidatorInterface {
	public function __construct(public array $values){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(in_array($value,$this->values,true) === false){
			$values = implode(chr(32).chr(44).chr(32),array_map(static fn(mixed $v) => var_export($v,true),$this->values));
			throw new InvalidArgumentException('$'.$name.' must be one of : '.$values.' , given '.var_export($value,true));
		}
		return $value;
	}
}

?>