<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// Available php-cs-fixer rules can be found at https://mlocati.github.io/php-cs-fixer-configurator/#version:2.19
$rules = [
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
            'for',
            'foreach',
            'if',
            'switch',
            'while',
            'yield',
        ]
    ],
    'cast_spaces' => true,
    'class_attributes_separation' => true,
    'clean_namespace' => true,
    'comment_to_phpdoc' => true,
    'concat_space' => ['spacing' => 'one'],
    'fully_qualified_strict_types' => true,
    'no_superfluous_phpdoc_tags' => true,
    'binary_operator_spaces' => ['operators' => ['=' => 'single_space']],
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();

return $config->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
