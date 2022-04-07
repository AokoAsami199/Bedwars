<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\world\World;

interface IArena{
	public function getDisplayName() : string;

	public function setDisplayName(string $displayName) : void;

	public function getWorld() : World;

	public function getClonedWorld() : ?World;

	public function cloneWorld() : void;

	public function resetWorld() : void;

	public function saveData();
}