<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\Server;
use VietnamPMTeam\Bedwars\Struct\Struct;

class ArenaData implements Struct{
	public string $displayName, $world;

	public function build() : Arena{
		return new Arena(
			$this->displayName,
			Server::getInstance()->getWorldManager()->getWorldByName($this->world)
		);
	}
}