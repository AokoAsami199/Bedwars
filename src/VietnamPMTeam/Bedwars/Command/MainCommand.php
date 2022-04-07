<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use VietnamPMTeam\Bedwars\Loader;

class MainCommand extends Command{
	public function __construct(
		protected Loader $plugin
	){
		parent::__construct("bedwars", "Bedwars command", "/bedwars", ["bw"]);
		$this->setPermission("bedwars.command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
	}
}