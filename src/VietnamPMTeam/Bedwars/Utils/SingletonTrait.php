<?php

declare(strict_types=1);

namespace VietnamPMTeam\Bedwars\Utils;

use VietnamPMTeam\Bedwars\Loader;

trait SingletonTrait{

	/** @var static */
	private static $instance;

    final public function __construct(
		protected Loader $plugin
	){}

	public static function getInstance(): static{
		return static::$instance;
	}

    public function getPlugin(): Loader{
        return $this->plugin;
    }

	protected function onInit(): void{}

	public static function init(Loader $plugin): void{
		(static::$instance = new self($plugin))->onInit();
	}
}