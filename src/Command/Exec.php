<?php
namespace Dop\Command;

use Dop\Dop;
use Dop\Host\Host;
use function Dop\run;
use Dop\Task;
use function Dop\writeln;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class Exec extends Command
{
    private $dop;

    /**
     * @inheritDoc
     */
    public function __construct(Dop $dop)
    {
        parent::__construct('exec');
        $this->dop = $dop;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('exec');
        $this->setDescription('Execute a command');
        $this->setHelp('help');

        $this->addArgument(
            'command-exec',
            InputArgument::REQUIRED,
            'Command to be executed.'
        );

        $this->addOption(
            'inventory',
            'i',
            InputOption::VALUE_OPTIONAL,
            'Inventory file with hosts definitions. Defaults to: hosts.php, hosts.ini, hosts.json, hosts.yml'
        );

        $this->addOption(
            'hosts',
            'f',
            InputOption::VALUE_REQUIRED,
            'Execute only on these hosts, comma separated.'
        );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getArgument('command-exec');
        $inventory = $input->getOption('inventory');
        $hosts = $input->getOption('hosts');

        $this->dop->setOutput($output);
        $this->dop->createInventoryFrom($inventory);

        if (!empty($hosts)) {
            $hosts = [$this->dop->getInventory()->getHostByAlias($hosts)];
        } else {
            $hosts = $this->dop->getInventory()->getAllHosts();
        }

        $dop = $this->dop;
        $task = new Task($command, function () use ($command, $hosts, $dop) {
            $output = run($command);
            $dop->writeln($output);
        });

        foreach ($hosts as $host) {
            $this->dop->setHost($host);
            $task->run();
            $output->writeln('');
        }
    }
}
