<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use VietnamPMTeam\Bedwars\Loader;
use function mkdir;

abstract class FileDatabase extends Database{
	public function __construct(
		protected Loader $plugin,
		protected string $containDir,
		protected string $fileExt
	){
		$this->containDir .= DIRECTORY_SEPARATOR;
		@mkdir($plugin->getDataFolder() . $this->containDir, 0777, true);
	}
}