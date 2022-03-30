<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Closure;
use VietnamPMTeam\Bedwars\Loader;
use function basename;
use function file_get_contents;
use function file_put_contents;
use function glob;
use function json_decode;
use function json_encode;

class JsonDatabase extends FileDatabase{
	public function __construct(
		Loader $plugin,
		string $containDir
	){
		parent::__construct($plugin, $containDir, Database::TYPE_JSON);
	}

	public function load(Closure $callback) : void{
		foreach(glob($this->plugin->getDataFolder() . $this->containDir . "*." . $this->fileExt) as $fileName){
			$callback(
				basename($fileName, "." . $this->fileExt),
				json_decode(file_get_contents($fileName), true)
			);
		}
	}

	public function save(string $identifier, array $data) : void{
		file_put_contents(
			$this->plugin->getDataFolder() . $this->containDir . $identifier . "." . $this->fileExt,
			json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING)
		);
	}
}