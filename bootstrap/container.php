<?php

use Atlas\Pdo\ConnectionLocator;
use League\Container\Container;
use League\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sirius\Orm\CastingManager;
use Sirius\Orm\Connection;
use Sirius\Orm\Orm;
use Sirius\Orm\Relation\RelationBuilder;

$config = [
    'db_connection' => 'mysql:host=localhost;dbname=ormplayground',
    'db_user' => 'root',
    'db_password' => 'sirius',
];

$container = new Container();
$container->delegate(new League\Container\ReflectionContainer);

$container->add(EventDispatcherInterface::class, EventDispatcher::class);

$container->add(ConnectionLocator::class, function() use ($config) {
    $connectionLocator = \Sirius\Orm\ConnectionLocator::new(Connection::new($config['db_connection'], $config['db_user'], $config['db_password']));
    $connectionLocator->logQueries();

    return $connectionLocator;
});

$container->add(Orm::class, function() use ($container) {
    return new Sirius\Orm\Orm(
        $container->get(ConnectionLocator::class),
        $container->get(RelationBuilder::class),
        $container->get(CastingManager::class),
        $container->get(EventDispatcherInterface::class),
        new \app\MapperLocator($container)
    );
});
