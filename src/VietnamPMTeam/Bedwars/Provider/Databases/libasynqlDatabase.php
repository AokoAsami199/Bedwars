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

	public function __construct(
		Loader $plugin
	){
		$sqlMap = [
			Database::TYPE_MYSQL => Database::SQL . Database::TYPE_MYSQL . ".sql",
			Database::TYPE_SQLITE => Database::SQL . Database::TYPE_SQLITE . ".sql",
		];
		$this->connector = libasynql::create($plugin, $plugin->getConfig()->get("database"), $sqlMap);
	}

	public function load(Closure $callback) : void{
	}

	public function save(string $identifier, array $data) : void{
	}

	public function asyncSelect(string $query, array $args = []) : Generator{
		$this->connector->executeSelect($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function asyncInsert(string $query, array $args = []) : Generator{
		$this->connector->executeInsert($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function close() : void{
		$this->connector->close();
	}
}