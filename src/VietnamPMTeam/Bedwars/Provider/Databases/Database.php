<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Closure;

abstract class Database{
	public const TYPE_JSON = "json";
	public const TYPE_YAML = "yml";
	public const TYPE_MYSQL = "mysql";
	public const TYPE_SQLITE = "sqlite";

	public const SQL = "sql" . DIRECTORY_SEPARATOR;
	public const ARENAS = "arenas" . DIRECTORY_SEPARATOR;

	/**
	 * @param Closure(string, array): void $callback
	 */
	abstract public function load(Closure $callback) : void;

	abstract public function save(string $identifier, array $data) : void;
}