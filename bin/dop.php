<?php // @codingStandardsIgnoreFile
namespace Dop;

use Symfony\Component\Console\Application;

chdir(dirname(__DIR__));

date_default_timezone_set('UTC');

if (file_exists($a = __DIR__ . '/../vendor/autoload.php')) {
    require $a;
} else {
    fwrite(STDERR, 'Cannot locate autoloader; please run "composer install"' . PHP_EOL);
    exit(1);
}

/** @var \Interop\Container\ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$application = new Application('DOP', Version::VERSION);

$commands = $container->get('config')['commands'];
foreach ($commands as $command) {
    $application->add($container->get($command));
}

$application->run();
