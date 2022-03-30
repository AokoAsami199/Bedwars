<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars;

use pocketmine\plugin\PluginBase;
use VietnamPMTeam\Bedwars\Arena\ArenaManager;
use VietnamPMTeam\Bedwars\Provider\DataProvider;
use VietnamPMTeam\Bedwars\Utils\Configuration;

class Loader extends PluginBase{
    public const CLASSES = [
        Configuration::class,
        ArenaManager::class,
        DataProvider::class
    ];

    protected function onEnable(): void{
        foreach(self::CLASSES as $class){
            $class::init($this);
        }
    }

    protected function onDisable(): void{
        DataProvider::getInstance()->close();
    }
}