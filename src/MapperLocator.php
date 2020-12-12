<?php
namespace app;

use League\Container\Container;
use Sirius\Orm\Contract\MapperLocatorInterface;
use Sirius\Orm\Mapper;

class MapperLocator implements MapperLocatorInterface
{

    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get($name)
    {
        $mapper = $this->container->get($name);
        if (!$mapper instanceof Mapper) {
            throw new \InvalidArgumentException($name . ' does not point to a valid mapper class');
        }

        return $mapper;
    }
}
