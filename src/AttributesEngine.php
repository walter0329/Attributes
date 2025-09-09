<?php

declare(strict_types = 1);

namespace Tak\Attributes;

use Tak\Attributes\Property\Set;

use Tak\Attributes\Property\Get;

use Attribute;

use Reflector;

use ReflectionClass;

use ReflectionMethod;

use ReflectionProperty;

use ReflectionAttribute;

use ReflectionNamedType;

use Error;

use BadMethodCallException;

trait AttributesEngine {
	private static array $metadataCache = array();

	public function __call(string $name,array $args) : mixed {
		return $this->invokeValidatedMethod(static::class,$name,$args);
	}
	public static function __callStatic(string $name,array $args) : mixed {
		$staticClass = new ReflectionClass(static::class);
		$instance = $staticClass->newInstanceWithoutConstructor();
		return $instance->invokeValidatedMethod(static::class,$name,$args);
	}
	public function __set(string $name,mixed $value) : void {
		if(property_exists($this,$name)){
			$reflection = new ReflectionProperty($this,$name);
			$identifier = 'property::'.$reflection->getDeclaringClass()->getName().'::'.$reflection->getName();
			if(array_key_exists($identifier,self::$metadataCache) === false){
				self::$metadataCache[$identifier] = $this->buildMetadata($reflection);
			}
			$metas = self::$metadataCache[$identifier];
			$null = boolval($metas['allowsNull'] and is_null($value));
			$default = boolval($metas['hasDefault'] and call_user_func($metas['hasDefault']) === $value);
			if($null === false and $default === false){
				foreach($metas['setValidators'] as $validator){
					$value = $validator->check($name,$value);
				}
			}
			if(empty($metas['setValidators']) and empty($metas['getValidators'])){
				throw new Error('Cannot access property '.static::class.'::$'.$name);
			} else {
				$reflection->setValue($this,$value);
			}
		} else {
			throw new Error('Property '.$name.' does not exist on '.static::class);
		}
	}
	public function __get(string $name) : mixed {
		if(property_exists($this,$name)){
			$reflection = new ReflectionProperty($this,$name);
			$identifier = 'property::'.$reflection->getDeclaringClass()->getName().'::'.$reflection->getName();
			if(array_key_exists($identifier,self::$metadataCache) === false){
				self::$metadataCache[$identifier] = $this->buildMetadata($reflection);
			}
			$metas = self::$metadataCache[$identifier];
			$value = $reflection->getValue($this);
			foreach($metas['getValidators'] as $validator){
				$value = $validator->check($name,$value);
			}
			if(empty($metas['getValidators']) and empty($metas['setValidators'])){
				throw new Error('Cannot access property '.static::class.'::$'.$name);
			} else {
				return $value;
			}
		} else {
			throw new Error('Property '.$name.' does not exist on '.static::class);
		}
	}
	private function invokeValidatedMethod(string $className,string $methodName,array $arguments,bool $isAccessible = false) : mixed {
		if(method_exists($className,$methodName) === false){
			throw new BadMethodCallException('Method '.$className.'::'.$methodName.' does not exist');
		}
		$reflection = new ReflectionMethod($className,$methodName);
		if($reflection->isProtected() === false){
			throw new Error('Call to method '.$className.'::'.$methodName.'() that is not protected');
		}
		$identifier = 'method::'.$reflection->getDeclaringClass()->getName().'::'.$reflection->getName();
		if(array_key_exists($identifier,self::$metadataCache) === false){
			self::$metadataCache[$identifier] = $this->buildMetadata($reflection);
		}
		$metas = self::$metadataCache[$identifier];
		$parametersName = array_column($metas['parameters'],'name');
		$args = array();
		foreach($arguments as $key => $value){
			$index = is_string($key) ? array_search($key,$parametersName,true) : $key;
			$pMeta = $metas['parameters'][$index];
			$null = boolval($pMeta['allowsNull'] and is_null($value));
			$default = boolval($pMeta['hasDefault'] and call_user_func($pMeta['hasDefault']) === $value);
			if($null === false and $default === false){
				foreach($pMeta['validators'] as $validator){
					$value = $validator->validate($pMeta['name'],$value);
				}
				$isAccessible |= empty($pMeta['validators']) === false;
			}
			$args[$pMeta['name']] = $value;
		}
		$isAccessible |= empty($metas['performers']) === false;
		$isAccessible |= empty($metas['return']) === false;
		if(boolval($isAccessible) === false){
			throw new Error('Call to method '.$className.'::'.$methodName.'() from global scope');
		}
		$run = fn(mixed ...$params) : mixed => $reflection->invokeArgs($reflection->isStatic() ? null : $this,$params);
		foreach($metas['performers'] as $performer){
			$run = fn(mixed ...$params) : mixed => $performer->invoke($run,$params);
		}
		$result = call_user_func_array($run,$args);
		foreach($metas['return'] as $returnFilter){
			$result = $returnFilter->filter($identifier,$result);
		}
		return $result;
	}
	private function buildMetadata(Reflector $reflection) : array {
		if($reflection instanceof ReflectionMethod){
			$parameters = $reflection->getParameters();
			$args = array();
			foreach($parameters as $parameter){
				$type = $parameter->getType();
				$args []= array(
					'name'=>$parameter->getName(),
					'allowsNull'=>$parameter->allowsNull(),
					'hasDefault'=>$parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue(...) : false,
					'validators'=>$this->resolveAttributeInstances($parameter,ValidatorInterface::class)
				);
			}
			$returnFilters = $this->resolveAttributeInstances($reflection,ReturnFilterInterface::class);
			$invokes = $this->resolveAttributeInstances($reflection,InvokeInterface::class);
			return array(
				'parameters'=>$args,
				'return'=>$returnFilters,
				'performers'=>$invokes
			);
		} elseif($reflection instanceof ReflectionProperty){
			$type = $reflection->getType();
			return array(
				'name'=>$reflection->getName(),
				'allowsNull'=>boolval($type?->allowsNull() ?? true),
				'hasDefault'=>$reflection->hasDefaultValue() ? $reflection->getDefaultValue(...) : false,
				'setValidators'=>$this->resolveAttributeInstances($reflection,Set::class),
				'getValidators'=>$this->resolveAttributeInstances($reflection,Get::class)
			);
		}
	}
	private function resolveAttributeInstances(Reflector $reflection,string $class) : array {
		$attributes = $reflection->getAttributes($class,ReflectionAttribute::IS_INSTANCEOF);
		return array_map(static fn(ReflectionAttribute $attr) : object => $attr->newInstance(),$attributes);
	}
}

?>