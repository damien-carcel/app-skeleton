<?php

declare(strict_types=1);

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

$finder = new DefaultFinder();
$finder->name('*.php')->in('src');


$rules = [
    new Rule('App\User\Domain', [
        'App\User\Domain',
        Uuid::class,
        Assert::class,
    ], RuleInterface::TYPE_ONLY),
    new Rule('App\User\Application', [
        'App\User\Application',
        'App\User\Domain',
        Uuid::class,
    ], RuleInterface::TYPE_ONLY),
];

return new Configuration($rules, $finder);
