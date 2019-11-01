<?php

declare(strict_types=1);

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

$finder = new DefaultFinder();
$finder->name('*.php')->in('src');


$rules = [
    new Rule('Carcel\User\Domain', [
        'Carcel\User\Domain',
        'Ramsey\Uuid\Uuid',
        Assert::class,
    ], RuleInterface::TYPE_ONLY),
    new Rule('Carcel\User\Application', [
        'Carcel\User\Application',
        'Carcel\User\Domain',
        Uuid::class,
        MessageHandlerInterface::class,
    ], RuleInterface::TYPE_ONLY),
];

return new Configuration($rules, $finder);
