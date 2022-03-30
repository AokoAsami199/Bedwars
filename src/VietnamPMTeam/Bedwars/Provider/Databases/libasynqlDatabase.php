<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Generator;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitGenerator\Await;
use VietnamPMTeam\Bedwars\Loader;
use VietnamPMTeam\Bedwars\Utils\Closable;

class libasynqlDatabase implements Closable{
	protected DataConnector $connector;

	protected const AREAS_INIT = "bedwars.areas.init";
	protected const AREAS_SELECT_ALL = "bedwars.areas.select.all";
	protected const AREAS_SELECT_ID = "bedwars.areas.select.id";
	protected const AREAS_SELECT_DISPLAYNAME = "bedwars.areas.select.displayName";
	protected const AREAS_SELECT_WORLDNAME = "bedwars.areas.select.worldName";
	protected const AREAS_REMOVE = "bedwars.areas.remove";
	protected const AREAS_CREATE = "bedwars.areas.create";
	protected const AREAS_UPDATE_DISPLAYNAME = "bedwars.areas.update.displayName";

	public function __construct(
		protected Loader $plugin,
		protected string $sqlType
	){
		//NOOP
	}

	public function load() : void{
		$sqlMap = [
			Database::TYPE_MYSQL => Database::SQL . Database::TYPE_MYSQL . ".sql",
			Database::TYPE_SQLITE => Database::SQL . Database::TYPE_SQLITE . ".sql",
		];
		$this->connector = libasynql::create($this->plugin, [], $sqlMap);
		$this->connector->executeGeneric(self::AREAS_INIT);
	}

	public function asyncSelect(string $query, array $args = []) : Generator{
		$this->connector->executeSelect($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function AreasSelectAll() : Generator{
		return yield $this->asyncSelect(self::AREAS_SELECT_ALL);
	}

	public function AreasSelectId(string $id) : Generator{
		return yield $this->asyncSelect(self::AREAS_SELECT_ID, ["id" => $id]);
	}

	public function AreasSelectDisplayName(string $displayName) : Generator{
		return yield $this->asyncSelect(self::AREAS_SELECT_DISPLAYNAME, ["displayName" => $displayName]);
	}

	public function AreasSelectWorldName(string $worldName) : Generator{
		return yield $this->asyncSelect(self::AREAS_SELECT_WORLDNAME, ["worldName" => $worldName]);
	}

	public function AreasCreate(string $id, string $displayName, string $worldName){
	}

	public function close() : void{
		$this->connector->close();
	}
}