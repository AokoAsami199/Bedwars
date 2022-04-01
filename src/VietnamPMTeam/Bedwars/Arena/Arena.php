<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\Server;
use pocketmine\utils\Filesystem;
use pocketmine\world\World;
use function uniqid;

class Arena{
	protected ?World $clonedWorld = null;

	public function __construct(
		protected string $identifier,
		protected string $displayName,
		protected World $world
	){
	}

	public function getIdentifier() : string{
		return $this->identifier;
	}

	public function getDisplayName() : string{
		return $this->displayName;
	}

	public function setDisplayName(string $displayName) : void{
		$this->displayName = $displayName;
	}

	public function getWorld() : World{
		return $this->world;
	}

	public function getClonedWorld() : ?World{
		return $this->clonedWorld;
	}

	public function cloneWorld() : void{
		if($this->world->isLoaded()){
			$this->world->save();
		}
		$dataPath = Server::getInstance()->getDataPath();
		$worldName = $this->world->getFolderName();
		$newWorldName = uniqid($worldName);
		Filesystem::recursiveCopy($dataPath . $worldName, $dataPath . $newWorldName);
		Server::getInstance()->getWorldManager()->loadWorld($newWorldName);
		$this->clonedWorld = Server::getInstance()->getWorldManager()->getWorldByName($newWorldName);
	}

	public function resetWorld() : void{
		if($this->clonedWorld === null){
			return;
		}
		if($this->clonedWorld->isLoaded()){
			Server::getInstance()->getWorldManager()->unloadWorld($this->clonedWorld, true);
		}
		Filesystem::recursiveUnlink(Server::getInstance()->getDataPath() . $this->clonedWorld->getFolderName());
		$this->clonedWorld = null;
	}

	public function saveData() : ArenaData{
		$data = new ArenaData;
		$data->displayName = $this->displayName;
		$data->worldName = $this->world->getFolderName();
		return $data;
	}

	public static function parseIdentifier(string $displayName) : string{
		return mb_strtolower(str_replace(" ", "_", preg_replace("/[^a-zA-Z]+/", "", $displayName)));
	}
}