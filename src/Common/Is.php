<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Is implements ValidatorInterface {
	public function __construct(private array | string $types){
		if(is_string($types)){
			$this->types = array_map(trim(...),explode(chr(124),$types));
		}
	}
	public function validate(string $name,mixed $value) : mixed {
		$raise = false;
		foreach($this->types as $type){
			$raise |= str_contains(gettype($value),strtolower($type));
		}
		if(boolval($raise) === false){
			throw new InvalidArgumentException('$'.$name.' must be'.strval(count($this->types) > 1 ? ' one of ' : chr(32)).implode(chr(32).chr(44).chr(32),$this->types).' type');
		}
		return $value;
	}
}

?>