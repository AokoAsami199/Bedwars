<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider;

use VietnamPMTeam\Bedwars\Arena\ArenaData;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Provider\Databases\Database;
use VietnamPMTeam\Bedwars\Provider\Databases\JsonDatabase;
use VietnamPMTeam\Bedwars\Provider\Databases\libasynqlDatabase;
use VietnamPMTeam\Bedwars\Provider\Databases\YamlDatabase;
use VietnamPMTeam\Bedwars\Utils\Closable;
use VietnamPMTeam\Bedwars\Utils\Configuration;
use VietnamPMTeam\Bedwars\Utils\SingletonTrait;
use VietnamPMTeam\Bedwars\Utils\StructParser;

final class DataProvider implements Closable{
	use SingletonTrait;

	protected Database|libasynqlDatabase $arenaDatabase;

	protected function onInit() : void{
		$arenaDBType = Configuration::getInstance()->database["type"];
		$this->arenaDatabase = match ($arenaDBType) {
			Database::TYPE_JSON => new JsonDatabase($this->plugin, Database::ARENAS),
			Database::TYPE_YAML => new YamlDatabase($this->plugin, Database::ARENAS),
			Database::TYPE_MYSQL, Database::TYPE_SQLITE => new libasynqlDatabase(
				$this->plugin,
				$arenaDBType
			)
		};
		$this->loadArenas();
	}

	protected function loadArenas() : void{
		$this->arenaDatabase->load(function(string $identifier, array $data){
			ArenaManager::getInstance()->registerArena(
				ArenaManager::getInstance()->createFromData($identifier, StructParser::parse(new ArenaData, $data))
			);
		});
	}

	protected function saveArenas() : void{
		foreach(ArenaManager::getInstance()->getArenas() as $arena){
			$this->arenaDatabase->save(
				$arena->getIdentifier(),
				StructParser::emit($arena->saveData())
			);
		}
	}

	public function close() : void{
		$this->saveArenas();
		if($this->arenaDatabase instanceof libasynqlDatabase){
			$this->arenaDatabase->close();
		}
	}
}