<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Closure;
use Generator;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitGenerator\Await;
use VietnamPMTeam\Bedwars\Loader;
use VietnamPMTeam\Bedwars\Utils\Closable;

class libasynqlDatabase extends Database implements Closable{
	protected DataConnector $connector;

	protected const ARENAS_INIT = "bedwars.arenas.init";
	protected const ARENAS_SELECT_ALL = "bedwars.arenas.select.all";
	protected const ARENAS_SELECT_ID = "bedwars.arenas.select.id";
	protected const ARENAS_SELECT_DISPLAYNAME = "bedwars.arenas.select.displayName";
	protected const ARENAS_SELECT_WORLDNAME = "bedwars.arenas.select.worldName";
	protected const ARENAS_REMOVE = "bedwars.arenas.remove";
	protected const ARENAS_SAVE = "bedwars.arenas.save";
	protected const ARENAS_UPDATE_DISPLAYNAME = "bedwars.arenas.update.displayName";

	public function __construct(
		Loader $plugin
	){
		$sqlMap = [
			Database::TYPE_MYSQL => Database::SQL . Database::TYPE_MYSQL . ".sql",
			Database::TYPE_SQLITE => Database::SQL . Database::TYPE_SQLITE . ".sql",
		];
		$this->connector = libasynql::create($plugin, $plugin->getConfig()->get("database"), $sqlMap);
		$this->connector->executeGeneric(self::ARENAS_INIT);
	}

	public function load(Closure $callback) : void{
		Await::f2c(function() use($callback) : Generator{
			$rows = yield from $this->arenasSelectAll();
			foreach($rows as $data){
				$callback($data["identifier"], $data);
			}
		});
	}

	public function save(string $identifier, array $data) : void{
		Await::f2c(function() use($identifier, $data) : Generator{
			yield from $this->arenasSave($identifier, $data);
		});
	}

	public function asyncSelect(string $query, array $args = []) : Generator{
		$this->connector->executeSelect($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function asyncInsert(string $query, array $args = []) : Generator{
		$this->connector->executeInsert($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function arenasSelectAll() : Generator{
		return yield $this->asyncSelect(self::ARENAS_SELECT_ALL);
	}

	public function arenasSave(string $identifier, array $data) : Generator{
		$data["identifier"] = $identifier;
		return yield $this->asyncInsert(self::ARENAS_SAVE, $data);
	}

	public function arenasSelectId(string $id) : Generator{
		return yield $this->asyncSelect(self::ARENAS_SELECT_ID, ["identifier" => $id]);
	}

	public function arenasSelectDisplayName(string $displayName) : Generator{
		return yield $this->asyncSelect(self::ARENAS_SELECT_DISPLAYNAME, ["displayName" => $displayName]);
	}

	public function arenasSelectWorldName(string $worldName) : Generator{
		return yield $this->asyncSelect(self::ARENAS_SELECT_WORLDNAME, ["worldName" => $worldName]);
	}

	public function close() : void{
		$this->connector->close();
	}
}