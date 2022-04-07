<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena\Builder;

use Error;
use Generator;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use ReflectionProperty;
use SOFe\AwaitGenerator\Await;
use SOFe\AwaitStd\DisposeException;
use VietnamPMTeam\Bedwars\Arena\Arena;
use VietnamPMTeam\Bedwars\Arena\ArenaData;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Loader;
use VietnamPMTeam\Bedwars\Utils\StructParser;
use function array_filter;
use function array_map;
use function explode;
use function implode;
use function strtolower;

class ArenaBuilder{
	public static function open(Player $player, Loader $plugin, ?ArenaData $data = null) : void{
		$data ??= new ArenaData;
		Await::f2c(function() use($plugin, $player, $data) : Generator{
			$save = $plugin->getConfig()->get("save-builder");
			$cmds = [
				"exit" => "Exit the builder mode",
				"help" => "Show this help message",
				"info" => "Show list of arena information",
				"save" => "Save the arena",
				"setname <name>" => "Set the arena name",
				"setworld [name]" => "Set the arena world",
			];
			while(true){
				try{
					[$which, $event] = yield Await::race([
						"chat" => $plugin->getStd()->consumeNextChat($player),
						"join" => $plugin->getStd()->awaitEvent(
							PlayerJoinEvent::class,
							fn(PlayerJoinEvent $event) => $event->getPlayer() === $player,
							true,
							EventPriority::HIGHEST,
							false,
							$player
						)
					]);
					if($which === "join"){
						$player->sendMessage("You are still opening an ArenaBuilder! Type \"help\" to see commands or \"exit\" if you want to exit builder mode.");
						return;
					}
					$args = explode(" ", $event->getMessage());
					switch(strtolower($args[0])){
						case "exit":
							return;
						case "help":
							$player->sendMessage("Available commands: " . implode("\n",
								array_map(
									fn(string $cmd, string $desc) : string => $cmd . " - " . $desc,
									$cmds
								)
							));
							break;
						case "info":
							$player->sendMessage(implode("\n", array_map(
								fn(ReflectionProperty $property) : string =>
									$property->getName() . ": " . ($property->isInitialized($data) ?
										$property->getValue($data) : "Not set"),
								StructParser::properties($data)
							)));
							break;
						default:
							$player->sendMessage("You are still in builder mode! Type \"help\" to see commands or \"exit\" if you want to exit builder mode.");
							break;
						case "save":
							try{
								ArenaManager::getInstance()->registerArena(
									Arena::parseIdentifier($data->displayName),
									ArenaManager::getInstance()->createFromData($data)
								);
								return;
							}catch(Error){
								$player->sendMessage("Some data has not been set: " . implode(", ",
									array_map(
										fn(ReflectionProperty $property) : string => $property->getName(),
										array_filter(
											StructParser::properties($data),
											fn(ReflectionProperty $property) : bool => !$property->isInitialized($data)
										)
									)));
							}
							break;
						case "setname":
							$data->displayName = $args[1];
							break;
						case "setworld":
							$world = $player->getServer()->getWorldManager()->getWorldByName($args[1] ?? $player->getWorld()->getFolderName());
							if($world !== null){
								if($world->isLoaded()){
									$data->worldName = $world->getFolderName();
								}
							}
							break;
					}
				}catch(DisposeException){
					if(!$save){
						return;
					}
				}
			}
		});
	}
}