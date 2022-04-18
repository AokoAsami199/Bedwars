<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use VietnamPMTeam\Bedwars\Database\Database;

final class ArenaManager{
	/** @var array<string, Arena> */
	protected array $arenas = [];
	protected Database $database;

	public function __construct(){
	}

	/**
	 * @return array<string, Arena>
	 */
	public function getArenas() : array{
		return $this->arenas;
	}

	public function getArena(string $identifier) : ?Arena{
		return $this->arenas[$identifier] ?? null;
	}

	public function registerArena(string $identifier, Arena $arena) : void{
		$this->arenas[$identifier] = $arena;
	}

	public function unregisterArena(string $identifier) : void{
		unset($this->arenas[$identifier]);
	}
}