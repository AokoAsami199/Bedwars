<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars;

use pocketmine\plugin\PluginBase;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Command\MainCommand;
use VietnamPMTeam\Bedwars\Provider\DataProvider;
use VietnamPMTeam\Bedwars\Session\SessionManager;
use VietnamPMTeam\Bedwars\Utils\Closable;
use VietnamPMTeam\Bedwars\Utils\Configuration;

class Loader extends PluginBase{
	public const CLASSES = [
		Configuration::class,
		ArenaManager::class,
		SessionManager::class,
		DataProvider::class
	];

	protected function onEnable() : void{
		foreach(self::CLASSES as $class){
			$class::init($this);
		}
		$this->getServer()->getCommandMap()->register("bedwars", new MainCommand($this));
	}

	protected function onDisable() : void{
		foreach(self::CLASSES as $class){
			$instance = $class::getInstance();
			if($instance instanceof Closable){
				$instance->close();
			}
		}
	}
}