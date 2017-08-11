<?php
use Dop\Command;

return [
    'dependencies' => [
        'factories'  => [
            Dop\Dop::class => Dop\DopFactory::class,

            Command\Ssh::class => Command\CommandFactory::class,
            Command\Exec::class => Command\CommandFactory::class,
        ],
    ],
    'commands' => [
        Command\Ssh::class,
        Command\Exec::class,
    ]
];
