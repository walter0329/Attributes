<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Reverse implements ValidatorInterface {
	public function __construct(){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_string($value)){
			$chars = preg_split('//u',$value,-1,PREG_SPLIT_NO_EMPTY);
			return implode(strval(null),array_reverse($chars));
		} elseif(is_array($value)){
			return array_reverse($value);
		} else {
			throw new InvalidArgumentException('$'.$name.' must be array / string for Reverse');
		}
	}
}

?>