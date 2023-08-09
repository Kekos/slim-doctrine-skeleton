<?php declare(strict_types=1);

use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude([
        'views',
        'Printing/Domain/resource',
    ])
;

$config = new PhpCsFixer\Config();
$config->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers());

return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_opening_tag' => false,
    'echo_tag_syntax' => [
        'format' => 'long',
    ],
    'fully_qualified_strict_types' => true,
    'function_typehint_space' => true,
    'modernize_types_casting' => true,
    'no_php4_constructor' => true,
    'no_unused_imports' => true,
    'single_quote' => true,
    'strict_param' => true,
    'trailing_comma_in_multiline' => [
        'elements' => [
            'arrays',
            'parameters',
        ],
    ],
    MultilinePromotedPropertiesFixer::name() => true,
])
    ->setFinder($finder)
;
