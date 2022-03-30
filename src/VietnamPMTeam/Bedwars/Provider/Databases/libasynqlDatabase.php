<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Provider\Databases;

use Closure;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use VietnamPMTeam\Bedwars\Loader;

class libasynqlDatabase extends Database{
	protected DataConnector $connector;

	public function __construct(
		protected Loader $plugin,
		protected string $tableName,
		protected string $sqlType
	){
		$sqlMap = [
			Database::TYPE_MYSQL => Database::SQL . Database::TYPE_MYSQL . ".sql",
			Database::TYPE_SQLITE => Database::SQL . Database::TYPE_SQLITE . ".sql",
		];
		$this->connector = libasynql::create($plugin, [], $sqlMap);
		$this->connector->executeGeneric("bedwars.$tableName.create");
	}

	public function load(Closure $callback) : void{
		$this->connector->executeSelect(
			"bedwars." . $this->tableName . ".select",
			[],
			function(array $rows) use ($callback){
				foreach($rows as $result){
					$callback($result["identifier"], $result);
				}
			}
		);
	}

	public function save(string $identifier, array $data) : void{
		$identifierData = ["identifier" => $identifier];
		$this->connector->executeSelect(
			"bedwars." . $this->tableName . ".selectExists",
			$identifierData,
			function(array $rows) use ($data, $identifierData){
				if(count($rows) > 0){
					$this->connector->executeChange(
						"bedwars." . $this->tableName . ".update",
						$data,
					);
				}
				$data = array_merge($data, $identifierData);
				$this->connector->executeInsert(
					"bedwars." . $this->tableName . ".insert",
					$data
				);
			}
		);
	}

	public function close() : void{
		$this->connector->close();
	}
}