<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider;

use VietnamPMTeam\Bedwars\Arena\Arena;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Provider\Databases\Database;
use VietnamPMTeam\Bedwars\Provider\Databases\JsonDatabase;
use VietnamPMTeam\Bedwars\Provider\Databases\libasynqlDatabase;
use VietnamPMTeam\Bedwars\Provider\Databases\YamlDatabase;
use VietnamPMTeam\Bedwars\Utils\Configuration;
use VietnamPMTeam\Bedwars\Utils\SingletonTrait;

final class DataProvider{
	use SingletonTrait;

	protected Database $arenaDatabase;

	protected function onInit() : void{
		$arenaDBType = Configuration::getInstance()->database_type();
		$this->arenaDatabase = match ($arenaDBType) {
			Database::TYPE_JSON => new JsonDatabase($this->plugin, Database::ARENAS),
			Database::TYPE_YAML => new YamlDatabase($this->plugin, Database::ARENAS),
			Database::TYPE_MYSQL, Database::TYPE_SQLITE => new libasynqlDatabase(
				$this->plugin,
				Database::ARENAS,
				$arenaDBType
			)
		};
		$this->loadArenas();
	}

	protected function loadArenas() : void{
		$this->arenaDatabase->load(function(string $identifier, array $data){
			ArenaManager::getInstance()->addArena(new Arena($identifier, $data));
		});
	}

	protected function saveArenas() : void{
		foreach(ArenaManager::getInstance()->getArenas() as $arena){
			$this->arenaDatabase->save($arena->getIdentifier(), $arena->saveData());
		}
	}

	public function close() : void{
		$this->saveArenas();
		if($this->arenaDatabase instanceof libasynqlDatabase){
			$this->arenaDatabase->close();
		}
	}
}