<?php declare(strict_types=1);

$finder = Symfony\Component\Finder\Finder::create()
    ->exclude('vendor')
    ->name('*.php')
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$dive = [
    'blank_line_after_opening_tag' => false,
    'blank_line_before_statement' => [
        'statements' => [
            'continue',
            'declare',
            'return',
            'throw',
            'try',
        ],
    ],
    'braces' => false,
    'concat_space' => ['spacing' => 'one'],
    'constant_case' => ['case' => 'lower'],
    'declare_strict_types' => true,
    'increment_style' => ['style' => 'post'],
    'is_null' => false,
    'linebreak_after_opening_tag' => false,
    'native_constant_invocation' => false,
    'native_function_invocation' => false,
    'not_operator_with_successor_space' => true,
    'no_useless_else' => true,
    'ordered_imports' => [
        'imports_order' => ['class', 'function', 'const'],
        'sort_algorithm' => 'alpha',
    ],
    'phpdoc_to_comment' => false,
    'single_line_throw' => false,
    'trailing_comma_in_multiline' => true,
    'yoda_style' => false,
];

return (new PhpCsFixer\Config())
    ->setRules(array_merge(['@Symfony' => true, '@Symfony:risky' => true], $dive))
    ->setFinder($finder);
