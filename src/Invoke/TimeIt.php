<?php

declare(strict_types = 1);

namespace Tak\Attributes\Invoke;

use Tak\Attributes\InvokeInterface;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class TimeIt implements InvokeInterface {
	private static array $cache = array();

	public function __construct(public mixed $callable = null){
	}
	public function invoke(callable $callback,array $arguments) : mixed {
		$start = microtime(true);
		$result = call_user_func_array($callback,$arguments);
		$finish = microtime(true);
		if(is_callable($this->callable)){
			call_user_func($this->callable,$finish - $start);
		} else {
			echo 'It took ' , $finish - $start , ' seconds' , PHP_EOL;
		}
		return $result;
	}
}

?>