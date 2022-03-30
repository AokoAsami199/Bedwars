<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

use pocketmine\math\Vector3;
use function array_map;
use function explode;
use function implode;

final class Utils{
	public static function stringToVector(string $string) : Vector3{
		return new Vector3(...array_map("intval", explode(":", $string)));
	}

	public static function vectorToString(Vector3 $vector) : string{
		return implode(":", [
			$vector->getX(),
			$vector->getY(),
			$vector->getZ()
		]);
	}
}