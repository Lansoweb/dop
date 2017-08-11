<?php
namespace Dop\Host;

final class Inventory
{
    private $hosts = [];

    public function __construct(array $hosts)
    {
        foreach ($hosts as $alias => $host) {
            $this->hosts[$alias] = Host::createFromConfig($alias, $host);
        }
    }

    public function getAllHosts() : array
    {
        return array_values($this->hosts);
    }

    public function getHostByAlias(string $alias) : ?Host
    {
        return $this->hosts[$alias] ?? null;
    }
}
