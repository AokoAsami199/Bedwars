<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Closure;
use VietnamPMTeam\Bedwars\Loader;

class YamlDatabase extends FileDatabase{
	public function __construct(
		Loader $plugin,
		string $containDir
	){
		parent::__construct($plugin, $containDir, Database::TYPE_YAML);
	}

	public function load(Closure $callback) : void{
		foreach(glob($this->plugin->getDataFolder() . $this->containDir . "*." . $this->fileExt) as $fileName){
			$callback(
				basename($fileName, "." . $this->fileExt),
				yaml_parse_file($fileName)
			);
		}
	}

	public function save(string $identifier, array $data) : void{
		yaml_emit_file(
			$this->plugin->getDataFolder() . $this->containDir . $identifier . "." . $this->fileExt,
			$data
		);
	}
}