<?php

declare(strict_types = 1);

namespace Tak\Attributes\Invoke;

use Tak\Attributes\InvokeInterface;

use Revolt\EventLoop;

use Attribute;

use Throwable;

use function Amp\async;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Repeat implements InvokeInterface {
	public function __construct(public float $interval,public float $timeout = -1){
	}
	public function invoke(callable $callback,array $arguments) : mixed {
		$future = async($callback,...$arguments);
		$id = EventLoop::repeat($this->interval,function(string $id) use($callback,$arguments) : void {
			call_user_func_array($callback,$arguments);
		});
		if($this->timeout > 0){
			EventLoop::unreference(EventLoop::delay($this->timeout,fn() : null => EventLoop::cancel($id)));
		}
		return $future->await();
	}
}

?>