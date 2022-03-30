<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Session;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

final class SessionHandler implements Listener{
	public function handleJoin(PlayerJoinEvent $event) : void{
		SessionManager::getInstance()->createSession($event->getPlayer());
	}

	public function handleQuit(PlayerQuitEvent $event) : void{
		SessionManager::getInstance()->destroySession($event->getPlayer());
	}
}