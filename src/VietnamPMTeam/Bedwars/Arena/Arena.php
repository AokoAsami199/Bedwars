<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\world\World;

class Arena{
	public function __construct(
		protected string $displayName,
		protected World $world
	){
	}

	public function getDisplayName() : string{
		return $this->displayName;
	}

	public function getWorld() : World{
		return $this->world;
	}

	public function saveData() : ArenaData{
		$data = new ArenaData;
		$data->displayName = $this->displayName;
		$data->world = $this->world->getFolderName();
		return $data;
	}
}