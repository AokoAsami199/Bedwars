<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

final class Configuration implements Closable{
	use SingletonTrait;

	public array $database;

	protected function onInit() : void{
		$this->plugin->saveDefaultConfig();
		StructParser::parse($this, $this->plugin->getConfig()->getAll());
	}

	public function close() : void{
		$this->plugin->getConfig()->setAll(StructParser::emit($this));
		$this->plugin->saveConfig();
	}
}