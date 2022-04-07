<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena\Builder;

use Error;
use Generator;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ReflectionProperty;
use SOFe\AwaitGenerator\Await;
use SOFe\AwaitStd\DisposeException;
use VietnamPMTeam\Bedwars\Arena\Arena;
use VietnamPMTeam\Bedwars\Arena\ArenaData;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Loader;
use VietnamPMTeam\Bedwars\Utils\StructParser;
use function array_filter;
use function array_keys;
use function array_map;
use function explode;
use function implode;
use function strtolower;

class ArenaBuilder{
	public const COMMANDS = [
		"exit" => ["Exit the builder mode"],
		"help" => ["Show this help message"],
		"info" => ["Show a list of arena information"],
		"save" => ["Save the arena"],
		"setname" => ["Set the arena name", "<name>"],
		"setworld" => ["Set the arena world", "[name]"],
	];
	public const HELP_MSG = "Type \"help\" to see commands or \"exit\" if you want to exit builder mode.";

	protected ArenaData $data;

	public function __construct(
		protected Loader $plugin,
		?ArenaData $data = null,
		protected ?string $identifier = null
	){
		$this->data ??= $data ?? new ArenaData;
	}

	public function open(Player $player) : void{
		Await::f2c(function() use($player) : Generator{
			$player->sendMessage("Entering a new ArenaBuilder" . ($this->identifier === null ? "" : " in arena with identifier " . $this->identifier) . "...\n" . self::HELP_MSG);
			while(true){
				try{
					[$which, $event] = yield Await::race([
						"chat" => $this->plugin->getStd()->consumeNextChat($player),
						"join" => $this->plugin->getStd()->awaitEvent(
							PlayerJoinEvent::class,
							fn(PlayerJoinEvent $event) => $event->getPlayer() === $player,
							true,
							EventPriority::HIGHEST,
							false,
							$player
						)
					]);
					if($which === "join"){
						$player->sendMessage("You are still opening an ArenaBuilder! " . self::HELP_MSG);
						return;
					}
					$args = explode(" ", $event->getMessage());
					switch(strtolower($args[0])){
						case "exit":
							$player->sendMessage("Closing the ArenaBuilder...");
							return;
						case "help":
							$player->sendMessage("Available commands: \n" . implode("\n",
								array_map(
									fn(string $cmd, array $desc) : string => $cmd . " " . ($desc[1] ?? "") . " - " . $desc[0],
									array_keys(self::COMMANDS), self::COMMANDS
								)
							));
							break;
						case "info":
							$player->sendMessage(implode("\n", array_map(
								fn(ReflectionProperty $property) : string =>
									$property->getName() . ": " . ($property->isInitialized($this->data) ?
										$property->getValue($this->data) : "Not set"),
								StructParser::properties($this->data)
							)));
							break;
						default:
							$player->sendMessage("You are still in builder mode! " . self::HELP_MSG);
							break;
						case "save":
							try{
								$newIdentifier = $this->identifier ?? Arena::parseIdentifier($this->data->displayName);
								if(ArenaManager::getInstance()->getArena($newIdentifier) !== null && $this->identifier === null){
									while(true){
										$player->sendMessage("This arena with name " . $this->data->displayName . " already exists! Do you want to overwrite it? (y/n)");
										$event = yield $this->plugin->getStd()->consumeNextChat($player);
										$message = $event->getMessage() ;
										if($message === "y"){
											break;
										}
										if($message === "n"){
											$player->sendMessage("Closing the builder...");
											return;
										}
										$player->sendMessage("Please type \"y\" or \"n\"");
									}
								}
								$player->sendMessage("Successfully saved the arena with identifier: " . $newIdentifier);
								ArenaManager::getInstance()->registerArena(
									$newIdentifier,
									ArenaManager::getInstance()->createFromData($this->data)
								);
								return;
							}catch(Error){
								$player->sendMessage("Some data has not been set: " . implode(", ",
									array_map(
										fn(ReflectionProperty $property) : string => $property->getName(),
										array_filter(
											StructParser::properties($this->data),
											fn(ReflectionProperty $property) : bool => !$property->isInitialized($this->data)
										)
									)));
							}
							break;
						case "setname":
							$this->data->displayName = $args[1] . TextFormat::RESET;
							$player->sendMessage("Set the arena name to " . $args[1]);
							break;
						case "setworld":
							$world = $player->getServer()->getWorldManager()->getWorldByName($args[1] ?? $player->getWorld()->getFolderName());
							if($world !== null){
								$worldName = $world->getFolderName();
								if($world->isLoaded()){
									$this->data->worldName = $worldName;
									$player->sendMessage("Set the world to " . $worldName);
									break;
								}
								$player->sendMessage("The world " . $worldName . " is not loaded! Please load it first!");
							}
							$player->sendMessage("The world " . $args[1] . " does not exist!");
							break;
					}
				}catch(DisposeException){
					if(!$this->plugin->getConfig()->get("save-builder")){
						return;
					}
				}
			}
		});
	}
}