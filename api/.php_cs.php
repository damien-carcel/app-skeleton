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
            '@PHP73Migration' => true,
            'concat_space' => ['spacing' => 'one'],
            'linebreak_after_opening_tag' => true,
            'no_unreachable_default_argument_value' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'ordered_class_elements' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'psr4' => true,
            'self_accessor' => true,
            'single_line_throw' => false,
        ]
    );

$configuration->setFinder($finder);

return $configuration;
