<?php

declare(strict_types = 1);

namespace Tak\Attributes\Invoke;

use Tak\Attributes\InvokeInterface;

use Attribute;

use ReflectionFunction;

#[Attribute(Attribute::TARGET_METHOD)]
final class Cache implements InvokeInterface {
	private static array $cache = array();

	public function __construct(public float $ttl = 60){
	}
	public function invoke(callable $callback,array $arguments) : mixed {
		$timer = $this->ttl + microtime(true);
		$reflection = new ReflectionFunction($callback);
		$hash = md5(strval($reflection));
		if(array_key_exists($hash,self::$cache) === false || self::$cache[$hash]['expires_at'] <= microtime(true)){
			self::$cache[$hash] = array('expires_at'=>$timer,'result'=>call_user_func_array($callback,$arguments));
		}
		return self::$cache[$hash]['result'];
	}
}

?>