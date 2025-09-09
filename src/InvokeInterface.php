<?php

declare(strict_types = 1);

namespace Tak\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
interface InvokeInterface {
	public function invoke(callable $callback,array $arguments) : mixed;
}

?>