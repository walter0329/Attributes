<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL)]
final class IP implements ValidatorInterface {
	public const V4 = (1 << 4);
	public const V6 = (1 << 6);
	public const BOTH = self::V4 | self::V6;

	public function __construct(public int $flags = self::BOTH){
	}
	public function validate(string $name,mixed $value) : mixed {
		if(boolval($this->flags & self::V4)){
			if(filter_var($value,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
				return $value;
			}
		}
		if(boolval($this->flags & self::V6)){
			if(filter_var($value,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)){
				return $value;
			}
		}
		if(boolval($this->flags & self::BOTH) === false){
			throw new InvalidArgumentException('$'.$name.' The IP::V4 / IP::V6 flags are not set');
		}
		throw new InvalidArgumentException('$'.$name.' invalid IP , given '.var_export($value,true));
	}
}

?>