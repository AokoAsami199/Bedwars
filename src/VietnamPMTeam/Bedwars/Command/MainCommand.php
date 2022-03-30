<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use VietnamPMTeam\Bedwars\Command\Subcommands\BaseSubcommand;
use VietnamPMTeam\Bedwars\Command\Subcommands\ListCommand;
use VietnamPMTeam\Bedwars\Loader;
use function array_map;
use function array_merge;
use function array_shift;
use function implode;

class MainCommand extends Command{
	/** @var array<string, BaseSubcommand> $subcommands */
	protected array $subcommands = [];

	public function __construct(
		protected Loader $plugin
	){
		parent::__construct("bedwars", "Bedwars command", "/bedwars", ["bw"]);
		$this->registerSubcommands([
			"list" => [ListCommand::class, "Show list of all available arenas", "<page>"]
		]);
		$this->setUsage(implode("\n", array_merge(
			["Bedwars Commands:"],
			array_map(
				fn(BaseSubcommand $subCommand) : string =>
					$subCommand->getUsage() . " - " . $subCommand->getDescription(),
				$this->subcommands
			)
		)));
		$this->setPermission("bedwars.command");
	}

	protected function registerSubcommands(array $commands) : void{
		/** @phpstan-var array{class-string<BaseSubcommand>, string, string} */
		foreach($commands as $name => $options){
			[$class, $description, $usageMessage] = $options;
			$this->subcommands[$name] = new $class(
				$this->plugin,
				$name,
				$description,
				$usageMessage
			);
		}
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!isset($args[0]) || $args[0] === "help"){
			$sender->sendMessage($this->getUsage());
			return;
		}
		$subcommand = $this->subcommands[$args[0]] ?? null;
		if($subcommand === null){
			return;
		}
		array_shift($args);
		if(!$subcommand->execute($sender, $commandLabel, $args)){
			$sender->sendMessage($subcommand->getUsage());
			return;
		}
	}
}