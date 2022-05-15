<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{

		$configurator = new Configurator;
		$appDir = dirname(__DIR__);

		$isLocal = getenv("NETTE_DEBUG") == 1;

		$configurator->setDebugMode($isLocal);
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/services.neon');
        $configurator->addConfig($appDir . '/config/form_errors.neon');

        # ziskani konfiguracniho souboru podle instance
        $envConfig = $isLocal ? 'local.neon' : 'prod.neon';
        $envConfigPath = $appDir . '/config/' . $envConfig;

        if(!file_exists($envConfigPath)){
            echo "config file $envConfig not found";
            exit;
        }

		$configurator->addConfig($envConfigPath);

		return $configurator;
	}
}
