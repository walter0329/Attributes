<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use ReflectionClass;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Func implements ValidatorInterface {
	public function __construct(public mixed $callable,public bool $enableCheck = true){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(is_callable($this->callable)){
			$result = call_user_func($this->callable,$value);
			if($result === false and $this->enableCheck){
				throw new InvalidArgumentException('$'.$name.' the function returned false');
			}
			$ensure = boolval(is_bool($result) and $this->enableCheck);
			return $ensure ? $value : $result;
		} else {
			return $value;
		}
	}
}

?>