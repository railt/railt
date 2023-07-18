<?php

$files = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/libs/*/src')
;

$rules = [
    '@PER' => true,
    '@PER:risky' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
];

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setCacheFile(__DIR__ . '/vendor/.php-cs-fixer.cache')
    ->setFinder($files)
;
