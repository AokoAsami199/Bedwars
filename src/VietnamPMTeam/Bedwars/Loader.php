<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars;

use pocketmine\plugin\PluginBase;
use SOFe\AwaitStd\AwaitStd;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Command\MainCommand;
use VietnamPMTeam\Bedwars\Provider\DataProvider;
use VietnamPMTeam\Bedwars\Utils\Closable;

class Loader extends PluginBase{
	public const CLASSES = [
		ArenaManager::class,
		DataProvider::class
	];

	protected AwaitStd $std;

	public function getStd() : AwaitStd{
		return $this->std;
	}

	protected function onEnable() : void{
		$this->std = AwaitStd::init($this);
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
