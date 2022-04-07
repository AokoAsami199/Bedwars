<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use VietnamPMTeam\Bedwars\Utils\SingletonTrait;
use function array_rand;

final class ArenaManager{
	use SingletonTrait;

	/** @var array<string, IArena> */
	protected array $arenas = [];

	/**
	 * @return array<string, IArena>
	 */
	public function getArenas() : array{
		return $this->arenas;
	}

	public function getArena(string $identifier) : ?IArena{
		return $this->arenas[$identifier] ?? null;
	}

	public function getArenaByName(string $displayName) : ?IArena{
		foreach($this->arenas as $arena){
			if($arena->getDisplayName() === $displayName){
				return $arena;
			}
		}
		return null;
	}

	public function getRandomArena() : ?IArena{
		return empty($this->arenas) ? null : $this->arenas[array_rand($this->arenas)];
	}

	public function registerArena(string $identifier, IArena $arena) : void{
		$this->arenas[$identifier] = $arena;
	}

	public function unregisterArena(string $identifier) : void{
		unset($this->arenas[$identifier]);
	}

	public function createFromData(ArenaData $data) : Arena{
		return new Arena(
			$data->displayName,
			$this->plugin->getServer()->getWorldManager()->getWorldByName($data->worldName)
		);
	}
}