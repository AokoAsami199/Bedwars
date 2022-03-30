<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Session;

use pocketmine\player\Player;
use VietnamPMTeam\Bedwars\Utils\SingletonTrait;

final class SessionManager{
	use SingletonTrait;

	/** @var array<int, Session> */
	protected array $sessions = [];

	protected function onInit() : void{
		$this->plugin->getServer()->getPluginManager()->registerEvents(new SessionHandler, $this->plugin);
	}

	public function createSession(Player $player) : void{
		$this->sessions[$player->getId()] = new Session($player);
	}

	public function getSession(Player $player) : Session{
		if(!isset($this->sessions[$player->getId()])){
			$this->createSession($player);
		}
		return $this->sessions[$player->getId()];
	}

	public function destroySession(Player $player) : void{
		unset($this->sessions[$player->getId()]);
	}
}