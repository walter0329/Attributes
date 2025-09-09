<?php

declare(strict_types = 1);

namespace Tak\Attributes\Common;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Operators implements ValidatorInterface {
	public function __construct(public mixed $what,public string $operator){
	}
	public function validate(string $name,mixed $value) : mixed {
		$result = match(strtolower($this->operator)){
			'add' , '+' => $this->what + $value,
			'sub' , '-' => $this->what - $value,
			'mul' , '*' => $this->what * $value,
			'div' , '+' => $this->what / $value,
			'mod' , '%' => $this->what % $value,
			'pow' , '**' => $this->what ** $value,
			'concat' , '.' => $this->what . $value,
			'shl' , '<<' => $this->what << $value,
			'shr' , '>>' => $this->what >> $value,
			'xor' , '^' => $this->what ^ $value,
			'exclude' , '~' => $this->what &~ $value,
			'or' , '|' => $this->what || $value,
			'and' , '&' => $this->what && $value,
			'not' , '!' => $this->what != $value,
			'equal' , '=' => $this->what == $value,
			'lt' , '<' => $this->what < $value,
			'gt' , '>' => $this->what > $value,
			'lte' , '<=' => $this->what <= $value,
			'gte' , '>=' => $this->what >= $value,
			'spaceship' , '<=>' => $this->what <=> $value,
			'instanceof' , 'is' => $value instanceof $this->what,
			default => throw new InvalidArgumentException('Unsupported operator : '.$this->operator)
		};
		if($result === false){
			throw new InvalidArgumentException('NOT '.var_export($this->what,true).chr(32).$this->operator.chr(32).var_export($value,true));
		}
		return is_bool($result) ? $value : $result;
	}
}

?>