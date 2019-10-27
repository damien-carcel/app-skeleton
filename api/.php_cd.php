<?php

declare(strict_types=1);

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;

$finder = new DefaultFinder();
$finder->name('*.php')->in('src');


$rules = [
    new Rule('Carcel\User\Domain', [
        'Carcel\User\Domain',
        'Ramsey\Uuid\Uuid',
        'Webmozart\Assert\Assert',
    ], RuleInterface::TYPE_ONLY),
    new Rule('Carcel\User\Application', [
        'Carcel\User\Application',
        'Carcel\User\Domain',
        'Ramsey\Uuid\Uuid',
    ], RuleInterface::TYPE_ONLY),
];

return new Configuration($rules, $finder);
