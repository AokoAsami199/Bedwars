<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars;

use pocketmine\plugin\PluginBase;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Command\MainCommand;

final class Bedwars extends PluginBase{
	protected static self $instance;
	protected ArenaManager $arenaManager;

	public static function getInstance() : self{
		return self::$instance;
	}

	public function getArenaManager() : ArenaManager{
		return $this->arenaManager;
	}

	protected function onLoad() : void{
		$this->saveDefaultConfig();
		self::$instance = $this;
	}

	protected function onEnable() : void{
		$this->arenaManager = new ArenaManager;
		$this->getServer()->getCommandMap()->register("bedwars", new MainCommand);
	}

	protected function onDisable() : void{
	}
}