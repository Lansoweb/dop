<?php
namespace Dop;

function get($name, $default = null)
{
    $dop = Dop::get();
    return $dop->getConfig()->get($name, $default);
}

function set($name, $value)
{
    $dop = Dop::get();
    return $dop->getConfig()->set($name, $value);
}

function writeln(string $message)
{
    $dop = Dop::get();
    $dop->getOutput()->writeln($message);
}

function task($name, $body = null)
{
    return new Task($name, $body);
}

function run($command) : string
{
    $dop = Dop::get();

    $sshClient = $dop->getSshClient();
    $host = $dop->getHost();
    return $sshClient->run($host, $command);
}
