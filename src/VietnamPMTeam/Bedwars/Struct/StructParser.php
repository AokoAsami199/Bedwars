<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Struct;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use function is_subclass_of;

/**
 * Copied from Endermanbug ConfigStruct xD
 */
final class StructParser{
	/**
	 * @template T of Struct
	 * @phpstan-param T $struct
	 * @param array<string, mixed> $data
	 * @return T
	 */
	public static function parse(Struct $struct, array $data) : object{
		foreach(self::properties($struct) as $property){
			$name = $property->getName();
			if(!isset($data[$name])){
				continue;
			}
			$value = $data[$name];
			$type = $property->getType();
			if($type instanceof ReflectionNamedType){
				$typeClass = $type->getName();
				if(is_subclass_of($typeClass, Struct::class, true)){
					$value = self::parse(new $typeClass, $value);
				}
			}
			$property->setValue($struct, $value);
		}
		return $struct;
	}

	/**
	 * @return array<string, mixed>
	 */
	public static function emit(Struct $object) : array{
		$data = [];
		foreach(self::properties($object) as $property){
			$data[$property->getName()] = $property->getValue($object);
		}
		return $data;
	}

	/**
	 * @return ReflectionProperty[]
	 */
	public static function properties(object $object) : array{
		return (new ReflectionClass($object))->getProperties(ReflectionProperty::IS_PUBLIC);
	}
}