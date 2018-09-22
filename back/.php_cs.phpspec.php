<?php

declare(strict_types = 1);

/** @var \PhpCsFixer\ConfigInterface $configuration */
$configuration = (include '.php_cs.php');

$mainRules = $configuration->getRules();
$specificationRules = array_merge($mainRules, [
    'visibility_required' => ['elements' => ['const', 'property']],
    'void_return' => false,
]);

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in(__DIR__ . '/tests/spec');

$configuration->setRules($specificationRules)->setFinder($finder);

return $configuration;
