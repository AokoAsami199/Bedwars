<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

use ReflectionClass;
use ReflectionProperty;

/**
 * Copied from Endermanbug ConfigStruct xD
 */
final class StructParser{
	/**
	 * @throws \ReflectionException
	 */
	public static function parse(object $object, array $data) : void{
		$reflection = new ReflectionClass($object);
		foreach($data as $key => $value){
			$property = $reflection->getProperty($key);
			if(!$property->isPublic() || $property->isStatic()){
				continue;
			}
			$property->setAccessible(true);
			$property->setValue($object, $value);
		}
	}

	public static function emit(object $object) : array{
		$data = [];
		foreach((new ReflectionClass($object))->getProperties(ReflectionProperty::IS_PUBLIC) as $property){
			if($property->isStatic()){
				continue;
			}
			$property->setAccessible(true);
			$data[$property->getName()] = $property->getValue($object);
		}
		return $data;
	}
}