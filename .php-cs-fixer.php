<?php

$files = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/libs')
;

$rules = [
    '@PER-CS2.0' => true,
    '@PER-CS2.0:risky' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
];

return (new PhpCsFixer\Config())
    ->setRules($rules)
    ->setCacheFile(__DIR__ . '/vendor/.php-cs-fixer.cache')
    ->setFinder($files)
;
