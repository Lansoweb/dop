<?php
namespace Dop\Helper;

use Dop\Host\Host;
use Symfony\Component\Process\Process;

class SshClient
{
    public function run(Host $host, string $command) : string
    {
        $process = new Process("ssh {$host->getSshOptions()} 'bash -s; printf \"{{dop_exit:%s}}\" $?;'");
        $process->setInput($command);
        $process->setTimeout(3600);
        $process->setIdleTimeout(60);
        $process->run();
        $exitCode = $this->parseExitStatus($process->getOutput());
        $output = $this->filterOutput($process->getOutput());

        if ($exitCode !== 0) {
            throw new \RuntimeException(
                $process->getErrorOutput()
            );
        }

        return $output;
    }

    private function parseExitStatus(string $output) : int
    {
        preg_match('/\{{dop_exit:(.*?)\}}/', $output, $match);
        if (!isset($match[1])) {
            return -1;
        }
        $exitCode = (int)$match[1];
        return $exitCode;
    }

    private function filterOutput(string $output) : string
    {
        return preg_replace('/\{{dop_exit:(.*?)\}}/', '', $output);
    }
}
