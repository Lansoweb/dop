<?php
namespace Dop\Command;

use Dop\Dop;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($container->get(Dop::class));
    }
}
