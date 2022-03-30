<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use VietnamPMTeam\Bedwars\Utils\SingletonTrait;

final class ArenaManager{
	use SingletonTrait;

	/** @var array<string, Arena> */
	protected array $arenas = [];

	/**
	 * @return array<string, Arena>
	 */
	public function getArenas() : array{
		return $this->arenas;
	}

	public function getArena(string $identifier) : ?Arena{
		return $this->arenas[$identifier] ?? null;
	}

	public function getArenaByName(string $displayName) : ?Arena{
		foreach($this->arenas as $arena){
			if($arena->getDisplayName() === $displayName){
				return $arena;
			}
		}
		return null;
	}

	public function getRandomArena() : ?Arena{
		return empty($this->arenas) ? null : $this->arenas[array_rand($this->arenas)];
	}

	public function addArena(Arena $arena) : void{
		$this->arenas[$arena->getIdentifier()] = $arena;
	}

	public function removeArena(string $identifier) : void{
		unset($this->arenas[$identifier]);
	}
}