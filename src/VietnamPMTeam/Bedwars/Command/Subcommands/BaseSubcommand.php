<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command\Subcommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use VietnamPMTeam\Bedwars\Loader;

abstract class BaseSubcommand extends Command{
	public function __construct(
		protected Loader $plugin,
		string $name,
		string $description,
		?string $usageMessage = null
	){
		parent::__construct(
			$name,
			$description,
			"/bedwars " . $name . ($usageMessage === null ? "" : " " . $usageMessage)
		);
	}

	abstract public function execute(CommandSender $sender, string $commandLabel, array $args) : bool;
}