<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Vector implements ValidatorInterface {
	public function __construct(protected ValidatorInterface $validator){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_array($value) === false){
			throw new InvalidArgumentException('$'.$name.' must be array for Vector');
		}
		foreach($value as $k => $v){
			$value[$k] = $this->validator->validate($name,$v);
		}
		return $value;
	}
}

?>