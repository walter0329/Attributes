<?php

declare(strict_types = 1);

namespace Tak\Attributes\Invoke;

use Tak\Attributes\InvokeInterface;

use Attribute;

use Throwable;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class IgnoreErrors implements InvokeInterface {
	public function __construct(public bool $log = true){
	}
	public function invoke(callable $callback,array $arguments) : mixed {
		try {
			return call_user_func_array($callback,$arguments);
		} catch(Throwable $error){
			if($this->log){
				error_log(strval($error));
			}
			return null;
		}
	}
}

?>