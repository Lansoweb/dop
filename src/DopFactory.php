<?php
namespace Dop;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DopFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dop = new Dop();

        return $dop;
    }
}
