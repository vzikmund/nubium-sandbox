<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);

$explorer = $container->getByType(\Nette\Database\Explorer::class);
dump($explorer->table("user")->count());
exit;
$application->run();
