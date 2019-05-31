<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

$configuration = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR2' => true,
            '@Symfony' => true,
            '@PHP71Migration' => true,
            '@PHP71Migration:risky' => true,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'none'],
            'linebreak_after_opening_tag' => true,
            'no_unreachable_default_argument_value' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'ordered_class_elements' => true,
            'ordered_imports' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_order' => true,
            'psr4' => true,
        ]
    );

$configuration->setFinder($finder);

return $configuration;
