<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests/acceptance')
    ->in(__DIR__.'/tests/end-to-end')
    ->in(__DIR__.'/tests/fixtures')
    ->in(__DIR__.'/tests/integration')
    ->notName('Kernel.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            '@Symfony:risky' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'single_line_throw' => false,
        ]
    )
    ->setFinder($finder)
    ->setCacheFile('.php-cs-fixer.cache');
