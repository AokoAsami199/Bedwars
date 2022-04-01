<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command\Subcommands;

use pocketmine\command\CommandSender;
use VietnamPMTeam\Bedwars\Arena\Arena;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;

use function array_map;
use function implode;

class ListCommand extends BaseSubcommand{
	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		$sender->sendMessage("List of all available arenas: " . implode(" ", array_map(
			fn(Arena $arena) : string => $arena->getIdentifier(),
			ArenaManager::getInstance()->getArenas()
		)));
		return true;
	}
}