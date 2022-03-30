<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Session;

use pocketmine\player\Player;

class Session{
	public function __construct(
		protected Player $player
	){
	}

	public function getPlayer() : Player{
		return $this->player;
	}

	public function isSame(Session $session) : bool{
		return $session->player->getId() === $this->player->getId();
	}
}