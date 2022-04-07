<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

use ReflectionClass;
use ReflectionProperty;

/**
 * Copied from Endermanbug ConfigStruct xD
 */
final class StructParser{
	public static function parse(object $object, array $data) : void{
		foreach(self::properties($object) as $property){
			$name = $property->getName();
			if(!isset($data[$name])){
				continue;
			}
			$property->setAccessible(true);
			$property->setValue($object, $data[$name]);
		}
	}

	public static function emit(object $object) : array{
		$data = [];
		foreach(self::properties($object) as $property){
			$property->setAccessible(true);
			$data[$property->getName()] = $property->getValue($object);
		}
		return $data;
	}

	public static function properties(object $object) : array{
		return (new ReflectionClass($object))->getProperties(
			ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_STATIC
		);
	}
}