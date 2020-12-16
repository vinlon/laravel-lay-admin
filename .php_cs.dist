<?php

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor') //排除 vendor 目录
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PhpCsFixer' => true,
    'concat_space' => ['spacing' => 'one'],
    'single_line_comment_style' => false,
];

$config = new \PhpCsFixer\Config();
return $config->setRules($rules)
    ->setFinder($finder);

