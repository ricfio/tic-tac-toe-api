<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony'                               => true,
        '@Symfony:risky'                         => true,
        '@PHP71Migration:risky'                  => true,
        '@PSR1'                                  => true,
        '@PSR2'                                  => true,
        '@DoctrineAnnotation'                    => true,
        'align_multiline_comment'                => true,
        // 'array_indentation' => true,
        'array_syntax'                           => ['syntax' => 'short'],
        // 'combine_consecutive_unsets' => true,
        'binary_operator_spaces'                 => [
            'operators' => [
                '=>' => 'align',
                // '=' => 'align'
            ],
        ],
        'blank_line_after_opening_tag'           => false,
        'blank_line_before_statement'            => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
            ],
        ],
        'braces'                                 => [
            'allow_single_line_anonymous_class_with_empty_body' => true,
            'allow_single_line_closure'                         => true,
        ],
        // 'cast_spaces' => true,
        'class_attributes_separation'            => ['elements' => ['method' => 'one']],
        'class_definition'                       => ['single_line' => true],
        'concat_space'                           => ['spacing' => 'none'],
        // 'declare_equal_normalize' => true,
        'declare_strict_types'                   => true,
        'dir_constant'                           => true, // Replaces dirname(__FILE__) expression with equivalent __DIR__ constant.
        'fully_qualified_strict_types'           => true, // Transforms imported FQCN parameters and return types in function arguments to short version.
        'function_declaration'                   => false,
        'function_to_constant'                   => false,
        'function_typehint_space'                => false,
        'general_phpdoc_annotation_remove'       => ['annotations' => ['author', 'package']],
        'general_phpdoc_tag_rename'              => ['replacements' => ['inheritDocs' => 'inheritDoc']],
        'heredoc_to_nowdoc'                      => true,
        // 'include' => true,
        'linebreak_after_opening_tag'            => true, // Ensure there is no code on the same line as the PHP open tag.
        'list_syntax'                            => ['syntax' => 'short'],
        'lowercase_cast'                         => true,
        'method_argument_space'                  => ['on_multiline' => 'ensure_fully_multiline'],
        'modernize_types_casting'                => true, // Replaces intval, floatval, doubleval, strval and boolval function calls with according type casting operator.
//        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'multiline_whitespace_before_semicolons' => true, // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
        // 'native_function_casing' => true,
        // 'new_with_braces' => true,
        // 'no_blank_lines_after_class_opening' => true,
        // 'no_blank_lines_after_phpdoc' => true,
        // 'no_blank_lines_before_namespace' => true,
        // 'no_empty_comment' => true,
        // 'no_empty_phpdoc' => true,
        // 'no_empty_statement' => true,
        'no_extra_blank_lines'                   => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        // 'no_leading_import_slash' => true,
        // 'no_leading_namespace_whitespace' => true,
        // 'no_mixed_echo_print' => array('use' => 'echo'),
        // 'no_multiline_whitespace_around_double_arrow' => true,
        'no_php4_constructor'                    => true, // Convert PHP4-style constructors to __construct.
        // 'no_short_bool_cast' => true,
        // 'no_singleline_whitespace_before_semicolons' => true,
        // 'no_spaces_around_offset' => true,
        // 'no_superfluous_phpdoc_tags'             => [
        //     'allow_mixed'         => true,
        //     'allow_unused_params' => true,
        // ],
        // 'no_trailing_comma_in_list_call' => true,
        // 'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses'        => [
            'statements' => [
                'break',
                'clone',
                'continue',
                'echo_print',
                'return',
                'switch_case',
                'yield',
                'yield_from',
            ],
        ],
        'no_unneeded_curly_braces'               => ['namespaces' => true],
        'no_unreachable_default_argument_value'  => true, // In function arguments there must not be arguments with default values before non-default ones.
        'no_unused_imports'                      => true,
        'no_useless_else'                        => true,
        'no_useless_return'                      => true,
        // 'no_whitespace_before_comma_in_array' => true,
        // 'no_whitespace_in_blank_line' => true,
        // 'normalize_index_brace' => true,
        // 'object_operator_without_whitespace' => true,
        'operator_linebreak'                     => ['only_booleans' => true],
        'ordered_class_elements'                 => true, // Orders the elements of classes/interfaces/traits.
        'ordered_imports'                        => true,
        'phpdoc_add_missing_param_annotation'    => ['only_untyped' => false], // PHPDoc should contain @param for all params (for untyped parameters only).
        // 'phpdoc_align' => true,
        // 'phpdoc_annotation_without_dot' => true,
        // 'phpdoc_indent' => true,
        // 'phpdoc_inline_tag' => true,
        // 'phpdoc_no_access' => true,
        // 'phpdoc_no_alias_tag' => true,
        // 'phpdoc_no_empty_return' => true,
        // 'phpdoc_no_package' => true,
        // 'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order'                           => true, // Annotations in PHPDoc should be ordered so that @param annotations come first, then @throws annotations, then @return annotations.
        // 'phpdoc_return_self_reference' => true,
        // 'phpdoc_scalar' => true,
        // 'phpdoc_separation' => true,
        // 'phpdoc_single_line_var_spacing' => true,
        // 'phpdoc_summary' => true,
        'phpdoc_tag_type'                        => [
            'tags' => ['inheritDoc' => 'inline'],
        ],
        'phpdoc_to_comment'                      => false,
        // 'phpdoc_trim' => true,
        // 'phpdoc_types' => true,
        // 'phpdoc_var_without_name' => true,
        // 'php_unit_fqcn_annotation' => true,
        // 'php_unit_method_casing' => ['case' => 'snake_case'],
        'psr_autoloading'                        => true, // Class names should match the file name.
        // 'increment_style' => true,
        // 'return_type_declaration' => true,
        // 'self_accessor' => true,
        'semicolon_after_instruction'            => true,
        // 'short_scalar_cast' => true,
        'single_blank_line_before_namespace'     => true,
        // 'single_class_element_per_statement' => true,
        // 'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_quote'                           => true,
        'space_after_semicolon'                  => [
            'remove_in_empty_for_expressions' => true,
        ],
        // 'standardize_not_equals' => true,
        // 'ternary_operator_spaces' => true,
        // 'trailing_comma_in_multiline_array' => true,
        // 'trim_array_spaces' => true,
        // 'unary_operator_spaces' => true,
        // 'whitespace_after_comma_in_array' => true,
        // 'space_after_semicolon' => true,
        'single_blank_line_at_eof'               => true,
    ])
    // ->setIndent("\t")
    // ->setLineEnding("\n")
    ;
