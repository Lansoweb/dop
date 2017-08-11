<?php
namespace Dop;

use Dop\Helper\SshClient;
use Dop\Host\Host;
use Dop\Host\Inventory;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Config\Factory;
use Zend\Stdlib\Parameters;

final class Dop
{
    private static $instance;
    private $inventory;
    private $config;
    private $output;
    private $host;
    private $sshClient;

    public function __construct()
    {
        $this->config = new Parameters();
        $this->sshClient = new SshClient();
        self::$instance = $this;
    }

    public static function get()
    {
        return self::$instance;
    }

    /**
     * @return Parameters
     */
    public function getConfig(): Parameters
    {
        return $this->config;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() : OutputInterface
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return Host
     */
    public function getHost() : Host
    {
        return $this->host;
    }

    /**
     * @param Host $host
     */
    public function setHost(Host $host)
    {
        $this->host = $host;
    }

    /**
     * @return SshClient
     */
    public function getSshClient(): SshClient
    {
        return $this->sshClient;
    }

    public function createInventoryFrom(?string $filename) : void
    {
        if (empty($filename)) {
            $filename = array_filter(['hosts.php', 'hosts.ini', 'hosts.json', 'hosts.yml'], function ($filename) {
                return is_readable($filename);
            });
            $filename = $filename[0] ?? '';
        }

        if (!is_readable($filename)) {
            throw new \Exception(sprintf('Inventory file "%s" not readable!', $filename));
        }

        $hosts = Factory::fromFile($filename);
        $this->inventory = new Inventory($hosts);
    }

    /**
     * @return Inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    public function writeln(string $message)
    {
        $alias = $this->getHost()->getAlias();
        foreach (explode("\n", rtrim($message)) as $line) {
            $line = "[$alias]: <info>$line</info>";
            $this->output->writeln($line);
        }
    }
}
