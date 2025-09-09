<?php

declare(strict_types = 1);

namespace Tak\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
interface PropertyInterface {
	public function check(string $name,mixed $value) : mixed;
}

?>