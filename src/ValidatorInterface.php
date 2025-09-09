<?php

declare(strict_types = 1);

namespace Tak\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
interface ValidatorInterface {
	public function validate(string $name,mixed $value) : mixed;
}

?>