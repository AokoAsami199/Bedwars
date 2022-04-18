<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;
use VietnamPMTeam\Bedwars\Struct\Struct;

final class PositionData implements Struct{
	public float|int $x, $y, $z;
	public string $world;
	public float $yaw, $pitch;

	public function setPosition(Vector3 $position) : void{
		$this->x = $position->x;
		$this->y = $position->y;
		$this->z = $position->z;
		if($position instanceof Position && $position->isValid()){
			$this->world = $position->getWorld()->getFolderName();
		}
		if($position instanceof Location){
			$this->yaw = $position->yaw;
			$this->pitch = $position->pitch;
		}
	}

	public function getWorld() : ?World{
		return isset($this->world) ? Server::getInstance()->getWorldManager()->getWorldByName($this->world) : null;
	}

	public function toVector3() : Vector3{
		return new Vector3($this->x, $this->y, $this->z);
	}

	public function toPosition() : Position{
		return new Position($this->x, $this->y, $this->z, $this->getWorld());
	}

	public function toLocation() : Location{
		return new Location($this->x, $this->y, $this->z, $this->getWorld(), $this->yaw, $this->pitch);
	}
}