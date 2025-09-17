<?php

declare(strict_types = 1);

namespace Tak\Attributes\Return;

use Tak\Attributes\ReturnFilterInterface;

use Tak\Attributes\ValidatorInterface;

use Iterator;

use Generator;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Iterate implements ReturnFilterInterface {
	public function __construct(protected ValidatorInterface $validator){
	}
	public function filter(string $name,mixed $value) : mixed {
		if($value instanceof Iterator){
			for($value->rewind(); $value->valid(); $value->next()){
				$k = $value->key();
				$current = $value->current();
				$v = $this->validator->validate('YIELD',$current);
				yield $k => $v;
			}
		}
		return $value instanceof Generator ? $value->getReturn() : $value;
	}
}

?>