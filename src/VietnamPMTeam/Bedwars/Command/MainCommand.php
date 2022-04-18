<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use function array_keys;
use function array_map;
use function implode;
use function in_array;

class MainCommand extends Command{
	public const COMMANDS = [
		"help" => ["Show this help message"]
	];
	public const USE_INGAME = "Please use this command in-game.";

	public function __construct(){
		parent::__construct("bedwars", "Bedwars command", "Usage: /bedwars", ["bw"]);
		$this->setPermission("bedwars.command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!isset($args[0]) || !in_array($args[0], array_keys(self::COMMANDS), true)){
			$this->sendUsage($sender, "help");
			return;
		}
		switch($args[0]){
			case "help":
				$sender->sendMessage("Available commands: \n" . implode("\n",
					array_map(
						fn(string $cmd, array $desc) : string =>
							"/bedwars $cmd " . ($desc[1] ?? "") . " - $desc[0]",
						array_keys(self::COMMANDS), self::COMMANDS
					)
				));
				break;
		}
	}

	protected function sendUsage(CommandSender $sender, string $subcommand) : void{
		$usage = $this->getUsage();
		if($subcommand !== null && isset(self::COMMANDS[$subcommand])){
			$usage .= " $subcommand";
		}
		if(isset(self::COMMANDS[$subcommand][1])){
			$usage .= " " . self::COMMANDS[$subcommand][1];
		}
		$sender->sendMessage($usage);
	}
}