<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Arena;

use pocketmine\Server;
use pocketmine\world\World;

class Arena{
    protected string $displayName;
    protected World $world;

    /**
     * @param array<string, mixed> $rawData
     */
    public function __construct(
        protected string $identifier,
        protected array $rawData
    ){
        $this->parseData();
    }

    public function getIdentifier(): string{
        return $this->identifier;
    }

    public function getDisplayName(): string{
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): void{
        $this->displayName = $displayName;
    }

    public function getWorld(): World{
        return $this->world;
    }

    public function getFileName(): string{
        return $this->fileName;
    }

    public function parseData(): void{
        $this->displayName = $this->rawData["displayName"];
        $this->world = Server::getInstance()->getWorldManager()->getWorldByName($this->rawData["world"]);
    }

    public function saveData(): array{
        $this->rawData["displayName"] = $this->displayName;
        $this->rawData["world"] = $this->world->getFolderName();
        return $this->rawData;
    }
}