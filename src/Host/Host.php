<?php
namespace Dop\Host;

use Assert\Assertion;

class Host
{
    private $alias;
    private $address;
    private $port = 22;
    private $user = 'root';
    private $identityKey;
    private $groups = [];

    /**
     * @param array $config
     * @param string $alias
     * @return Host
     */
    public static function createFromConfig(string $alias, array $config)
    {
        $host = new self();
        $host->alias = $alias;
        $host->address = $config['address'] ?? $alias;
        $host->port = (int) ($config['port'] ?? 22);
        $host->user = $config['user'] ?? 'root';
        $host->identityKey = $config['identityKey'] ?? null;
        $host->groups = $config['groups'] ?? [];
        return $host;
    }

    /**
     * @return string
     */
    public function getSshOptions()
    {
        $options = "{$this->user}@{$this->address} -p {$this->port}";
        if (!empty($this->identityKey)) {
            $options .= " -i {$this->identityKey}";
        }
        return $options;
    }

    /**
     * @return string
     */
    public function getAlias() : string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getIdentityKey() : string
    {
        return $this->identityKey;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
