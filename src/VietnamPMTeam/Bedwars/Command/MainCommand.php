<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use VietnamPMTeam\Bedwars\Arena\Arena;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Arena\Builder\ArenaBuilder;
use VietnamPMTeam\Bedwars\Loader;
use function array_keys;
use function array_map;
use function implode;
use function in_array;

class MainCommand extends Command{
	public const COMMANDS = [
		"create" => ["Create a new arena"],
		"edit" => ["Edit an existing arena", "<name>"],
		"help" => ["Show this help message"],
		"list" => ["Show a list of available arenas"],
		"remove" => ["Remove an existing arena", "<name>"]
	];
	public const USE_INGAME = "Please use this command in-game.";

	public function __construct(
		protected Loader $plugin
	){
		parent::__construct("bedwars", "Bedwars command", "Usage: /bedwars", ["bw"]);
		$this->setPermission("bedwars.command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!isset($args[0]) || !in_array($args[0], array_keys(self::COMMANDS), true)){
			$this->sendUsage($sender, "help");
			return;
		}
		switch($args[0]){
			case "create":
				if($sender instanceof Player){
					(new ArenaBuilder($this->plugin))->open($sender);
					return;
				}
				$sender->sendMessage(self::USE_INGAME);
				break;
			case "edit":
				if($sender instanceof Player){
					if(!isset($args[1])){
						$this->sendUsage($sender, $args[0]);
						return;
					}
					$arena = ArenaManager::getInstance()->getArena($args[1]);
					if($arena === null){
						$sender->sendMessage("Arena " . $args[1] . " doesn't exist.");
						return;
					}
					(new ArenaBuilder($this->plugin, $arena->saveData(), $args[1]))->open($sender);
					return;
				}
				$sender->sendMessage(self::USE_INGAME);
				break;
			case "help":
				$sender->sendMessage("Available commands: \n" . implode("\n",
					array_map(
						fn(string $cmd, array $desc) : string => "/bedwars " . $cmd . " " . ($desc[1] ?? "") . " - " . $desc[0],
						array_keys(self::COMMANDS), self::COMMANDS
					)
				));
				break;
			case "list":
				$arenas = ArenaManager::getInstance()->getArenas();
				$sender->sendMessage("Available arenas: " . implode(", ",
					array_map(
						fn(string $identifier, Arena $arena) : string => $identifier . "(" . $arena->getDisplayName() . ")",
						array_keys($arenas), $arenas
					)
				));
				break;
			case "remove":
				if($sender instanceof Player){
					if(!isset($args[1])){
						$this->sendUsage($sender, $args[0]);
						return;
					}
					if(ArenaManager::getInstance()->getArena($args[1]) === null){
						$sender->sendMessage("Arena " . $args[1] . " doesn't exist.");
						return;
					}
					ArenaManager::getInstance()->unregisterArena($args[1]);
					$sender->sendMessage("Arena " . $args[1] . " removed.");
					return;
				}
				$sender->sendMessage(self::USE_INGAME);
				break;
		}
	}

	protected function sendUsage(CommandSender $sender, string $subcommand) : void{
		$usage = $this->getUsage();
		if($subcommand !== null && isset(self::COMMANDS[$subcommand])){
			$usage .= " " . $subcommand;
		}
		if(isset(self::COMMANDS[$subcommand][1])){
			$usage .= " " . self::COMMANDS[$subcommand][1];
		}
		$sender->sendMessage($usage);
	}
}