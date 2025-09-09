<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL)]
final class Json implements ValidatorInterface {
	public function __construct(public bool $enableCheck = true,public int $flags = JSON_THROW_ON_ERROR,public int $depth = 512){
	}
	public function validate(string $name,mixed $value) : mixed {
		$validation = boolval($this->enableCheck and is_string($value));
		if($validation and json_validate($value) === false){
			throw new InvalidArgumentException('$'.$name.' invalid JSON , given '.var_export($value,true));
		}
		return $validation ? $value : json_encode($value,$this->flags,$this->depth);
	}
}

?>