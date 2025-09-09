<?php

declare(strict_types = 1);

namespace Tak\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
interface ReturnFilterInterface {
	public function filter(string $name,mixed $value) : mixed;
}

?>