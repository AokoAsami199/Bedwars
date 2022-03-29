<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

use VietnamPMTeam\Bedwars\Provider\Databases\Database;

final class Configuration{
    use SingletonTrait;

    protected function onInit(): void{
        $this->plugin->saveDefaultConfig();
    }

    public function database_type(): string{
        return $this->plugin->getConfig()->getNested("database.type", Database::TYPE_JSON);
    }
}