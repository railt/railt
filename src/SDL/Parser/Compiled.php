<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

/**
 * This is generated file.
 * Do not update it manually.
 * Generated at 19-01-2018 17:15:32
 */
class Compiled extends \Railt\Compiler\Parser
{
    /**
     * The list of defined tokens.
     */
    private const TOKENS = [
    'default' => [
        'T_NON_NULL' => [
            '!',
            null,
            true,
        ],
        'T_VAR' => [
            '\\$',
            null,
            true,
        ],
        'T_PARENTHESIS_OPEN' => [
            '\\(',
            null,
            true,
        ],
        'T_PARENTHESIS_CLOSE' => [
            '\\)',
            null,
            true,
        ],
        'T_THREE_DOTS' => [
            '\\.\\.\\.',
            null,
            true,
        ],
        'T_COLON' => [
            ':',
            null,
            true,
        ],
        'T_EQUAL' => [
            '=',
            null,
            true,
        ],
        'T_DIRECTIVE_AT' => [
            '@',
            null,
            true,
        ],
        'T_BRACKET_OPEN' => [
            '\\[',
            null,
            true,
        ],
        'T_BRACKET_CLOSE' => [
            '\\]',
            null,
            true,
        ],
        'T_BRACE_OPEN' => [
            '{',
            null,
            true,
        ],
        'T_BRACE_CLOSE' => [
            '}',
            'default',
            true,
        ],
        'T_OR' => [
            '\\|',
            null,
            true,
        ],
        'T_AND' => [
            '\\&',
            null,
            true,
        ],
        'T_ON' => [
            'on\\b',
            null,
            true,
        ],
        'T_NUMBER_VALUE' => [
            '\\-?(0|[1-9][0-9]*)(\\.[0-9]+)?([eE][\\+\\-]?[0-9]+)?\\b',
            null,
            true,
        ],
        'T_BOOL_TRUE' => [
            'true\\b',
            null,
            true,
        ],
        'T_BOOL_FALSE' => [
            'false\\b',
            null,
            true,
        ],
        'T_NULL' => [
            'null\\b',
            null,
            true,
        ],
        'T_MULTILINE_STRING_OPEN' => [
            '"""',
            'multiline_string',
            true,
        ],
        'T_STRING_OPEN' => [
            '"',
            'string',
            true,
        ],
        'T_TYPE' => [
            'type\\b',
            null,
            true,
        ],
        'T_TYPE_IMPLEMENTS' => [
            'implements\\b',
            null,
            true,
        ],
        'T_ENUM' => [
            'enum\\b',
            null,
            true,
        ],
        'T_UNION' => [
            'union\\b',
            null,
            true,
        ],
        'T_INTERFACE' => [
            'interface\\b',
            null,
            true,
        ],
        'T_SCHEMA' => [
            'schema\\b',
            null,
            true,
        ],
        'T_SCHEMA_QUERY' => [
            'query\\b',
            null,
            true,
        ],
        'T_SCHEMA_MUTATION' => [
            'mutation\\b',
            null,
            true,
        ],
        'T_SCHEMA_SUBSCRIPTION' => [
            'subscription\\b',
            null,
            true,
        ],
        'T_SCALAR' => [
            'scalar\\b',
            null,
            true,
        ],
        'T_DIRECTIVE' => [
            'directive\\b',
            null,
            true,
        ],
        'T_INPUT' => [
            'input\\b',
            null,
            true,
        ],
        'T_EXTEND' => [
            'extend\\b',
            null,
            true,
        ],
        'T_NAME' => [
            '([_A-Za-z][_0-9A-Za-z]*)',
            null,
            true,
        ],
        'T_WHITESPACE' => [
            '[\\xfe\\xff|\\x20|\\x09|\\x0a|\\x0d]+',
            null,
            false,
        ],
        'T_COMMENT' => [
            '#[^\\n]*',
            null,
            false,
        ],
        'T_COMMA' => [
            ',',
            null,
            false,
        ],
    ],
    'multiline_string' => [
        'T_MULTILINE_STRING' => [
            '(?:\\\\"""|(?!""").|\\s)+',
            null,
            true,
        ],
        'T_MULTILINE_STRING_CLOSE' => [
            '"""',
            'default',
            true,
        ],
    ],
    'string' => [
        'T_STRING' => [
            '[^"\\\\]+(\\\\.[^"\\\\]*)*',
            null,
            true,
        ],
        'T_STRING_CLOSE' => [
            '"',
            'default',
            true,
        ],
    ],
];

    public function __construct()
    {
        parent::__construct(
            self::TOKENS,
            [
                0 => new \Railt\Compiler\Rule\Repetition(0, 0, -1, 'Directive', null),
                1 => new \Railt\Compiler\Rule\Repetition(1, 0, -1, 'Definition', null),
                'Document' => new \Railt\Compiler\Rule\Concatenation('Document', [0,1,], '#Document'),
                'Definition' => new \Railt\Compiler\Rule\Choice('Definition', ['ObjectDefinition','InterfaceDefinition','EnumDefinition','UnionDefinition','SchemaDefinition','ScalarDefinition','InputDefinition','ExtendDefinition','DirectiveDefinition',], null),
                4 => new \Railt\Compiler\Rule\Token(4, 'T_BOOL_TRUE', null, -1, true),
                5 => new \Railt\Compiler\Rule\Token(5, 'T_BOOL_FALSE', null, -1, true),
                6 => new \Railt\Compiler\Rule\Token(6, 'T_NULL', null, -1, true),
                'ValueKeyword' => new \Railt\Compiler\Rule\Choice('ValueKeyword', [4,5,6,], null),
                8 => new \Railt\Compiler\Rule\Token(8, 'T_ON', null, -1, true),
                9 => new \Railt\Compiler\Rule\Token(9, 'T_TYPE', null, -1, true),
                10 => new \Railt\Compiler\Rule\Token(10, 'T_TYPE_IMPLEMENTS', null, -1, true),
                11 => new \Railt\Compiler\Rule\Token(11, 'T_ENUM', null, -1, true),
                12 => new \Railt\Compiler\Rule\Token(12, 'T_UNION', null, -1, true),
                13 => new \Railt\Compiler\Rule\Token(13, 'T_INTERFACE', null, -1, true),
                14 => new \Railt\Compiler\Rule\Token(14, 'T_SCHEMA', null, -1, true),
                15 => new \Railt\Compiler\Rule\Token(15, 'T_SCHEMA_QUERY', null, -1, true),
                16 => new \Railt\Compiler\Rule\Token(16, 'T_SCHEMA_MUTATION', null, -1, true),
                17 => new \Railt\Compiler\Rule\Token(17, 'T_SCALAR', null, -1, true),
                18 => new \Railt\Compiler\Rule\Token(18, 'T_DIRECTIVE', null, -1, true),
                19 => new \Railt\Compiler\Rule\Token(19, 'T_INPUT', null, -1, true),
                20 => new \Railt\Compiler\Rule\Token(20, 'T_EXTEND', null, -1, true),
                'Keyword' => new \Railt\Compiler\Rule\Choice('Keyword', [8,9,10,11,12,13,14,15,16,17,18,19,20,], null),
                'Number' => new \Railt\Compiler\Rule\Token('Number', 'T_NUMBER_VALUE', null, -1, true),
                'Nullable' => new \Railt\Compiler\Rule\Token('Nullable', 'T_NULL', null, -1, true),
                24 => new \Railt\Compiler\Rule\Token(24, 'T_BOOL_TRUE', null, -1, true),
                25 => new \Railt\Compiler\Rule\Token(25, 'T_BOOL_FALSE', null, -1, true),
                'Boolean' => new \Railt\Compiler\Rule\Choice('Boolean', [24,25,], null),
                27 => new \Railt\Compiler\Rule\Token(27, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                28 => new \Railt\Compiler\Rule\Token(28, 'T_MULTILINE_STRING', null, -1, true),
                29 => new \Railt\Compiler\Rule\Token(29, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                30 => new \Railt\Compiler\Rule\Concatenation(30, [27,28,29,], null),
                31 => new \Railt\Compiler\Rule\Token(31, 'T_STRING_OPEN', null, -1, false),
                32 => new \Railt\Compiler\Rule\Token(32, 'T_STRING', null, -1, true),
                33 => new \Railt\Compiler\Rule\Token(33, 'T_STRING_CLOSE', null, -1, false),
                34 => new \Railt\Compiler\Rule\Concatenation(34, [31,32,33,], null),
                'String' => new \Railt\Compiler\Rule\Choice('String', [30,34,], null),
                36 => new \Railt\Compiler\Rule\Token(36, 'T_NAME', null, -1, true),
                'Word' => new \Railt\Compiler\Rule\Choice('Word', [36,'ValueKeyword',], null),
                'Name' => new \Railt\Compiler\Rule\Concatenation('Name', ['Word',], '#Name'),
                39 => new \Railt\Compiler\Rule\Choice(39, ['String','Word','Keyword',], null),
                'Key' => new \Railt\Compiler\Rule\Concatenation('Key', [39,], '#Name'),
                41 => new \Railt\Compiler\Rule\Choice(41, ['String','Number','Nullable','Keyword','Object','List','Word',], null),
                'Value' => new \Railt\Compiler\Rule\Concatenation('Value', [41,], '#Value'),
                'ValueDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver',], null),
                44 => new \Railt\Compiler\Rule\Token(44, 'T_NON_NULL', null, -1, true),
                45 => new \Railt\Compiler\Rule\Repetition(45, 0, 1, 44, null),
                46 => new \Railt\Compiler\Rule\Concatenation(46, ['ValueListDefinition',45,], '#List'),
                47 => new \Railt\Compiler\Rule\Token(47, 'T_NON_NULL', null, -1, true),
                48 => new \Railt\Compiler\Rule\Repetition(48, 0, 1, 47, null),
                49 => new \Railt\Compiler\Rule\Concatenation(49, ['ValueScalarDefinition',48,], '#Type'),
                'ValueDefinitionResolver' => new \Railt\Compiler\Rule\Choice('ValueDefinitionResolver', [46,49,], null),
                51 => new \Railt\Compiler\Rule\Token(51, 'T_BRACKET_OPEN', null, -1, false),
                52 => new \Railt\Compiler\Rule\Token(52, 'T_NON_NULL', null, -1, true),
                53 => new \Railt\Compiler\Rule\Repetition(53, 0, 1, 52, null),
                54 => new \Railt\Compiler\Rule\Concatenation(54, ['ValueScalarDefinition',53,], '#Type'),
                55 => new \Railt\Compiler\Rule\Token(55, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueListDefinition', [51,54,55,], null),
                'ValueScalarDefinition' => new \Railt\Compiler\Rule\Choice('ValueScalarDefinition', ['Keyword','Word',], null),
                58 => new \Railt\Compiler\Rule\Token(58, 'T_BRACE_OPEN', null, -1, false),
                59 => new \Railt\Compiler\Rule\Repetition(59, 0, -1, 'ObjectPair', null),
                60 => new \Railt\Compiler\Rule\Token(60, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Railt\Compiler\Rule\Concatenation('Object', [58,59,60,], '#Object'),
                62 => new \Railt\Compiler\Rule\Token(62, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Railt\Compiler\Rule\Concatenation('ObjectPair', ['Key',62,'Value',], '#ObjectPair'),
                64 => new \Railt\Compiler\Rule\Token(64, 'T_BRACKET_OPEN', null, -1, false),
                65 => new \Railt\Compiler\Rule\Repetition(65, 0, -1, 'Value', null),
                66 => new \Railt\Compiler\Rule\Token(66, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Railt\Compiler\Rule\Concatenation('List', [64,65,66,], '#List'),
                68 => new \Railt\Compiler\Rule\Token(68, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                69 => new \Railt\Compiler\Rule\Token(69, 'T_MULTILINE_STRING', null, -1, true),
                70 => new \Railt\Compiler\Rule\Token(70, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                71 => new \Railt\Compiler\Rule\Concatenation(71, [68,69,70,], null),
                'Documentation' => new \Railt\Compiler\Rule\Concatenation('Documentation', [71,], '#Description'),
                73 => new \Railt\Compiler\Rule\Repetition(73, 0, 1, 'Documentation', null),
                74 => new \Railt\Compiler\Rule\Token(74, 'T_SCHEMA', null, -1, true),
                75 => new \Railt\Compiler\Rule\Repetition(75, 0, 1, 'Name', null),
                76 => new \Railt\Compiler\Rule\Repetition(76, 0, -1, 'Directive', null),
                77 => new \Railt\Compiler\Rule\Token(77, 'T_BRACE_OPEN', null, -1, false),
                78 => new \Railt\Compiler\Rule\Token(78, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinition', [73,74,75,76,77,'SchemaDefinitionBody',78,], '#SchemaDefinition'),
                80 => new \Railt\Compiler\Rule\Choice(80, ['SchemaDefinitionQuery','SchemaDefinitionMutation','SchemaDefinitionSubscription',], null),
                'SchemaDefinitionBody' => new \Railt\Compiler\Rule\Repetition('SchemaDefinitionBody', 0, -1, 80, null),
                82 => new \Railt\Compiler\Rule\Repetition(82, 0, 1, 'Documentation', null),
                83 => new \Railt\Compiler\Rule\Token(83, 'T_SCHEMA_QUERY', null, -1, false),
                84 => new \Railt\Compiler\Rule\Token(84, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionQuery', [82,83,84,'SchemaDefinitionFieldValue',], '#Query'),
                86 => new \Railt\Compiler\Rule\Repetition(86, 0, 1, 'Documentation', null),
                87 => new \Railt\Compiler\Rule\Token(87, 'T_SCHEMA_MUTATION', null, -1, false),
                88 => new \Railt\Compiler\Rule\Token(88, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionMutation', [86,87,88,'SchemaDefinitionFieldValue',], '#Mutation'),
                90 => new \Railt\Compiler\Rule\Repetition(90, 0, 1, 'Documentation', null),
                91 => new \Railt\Compiler\Rule\Token(91, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                92 => new \Railt\Compiler\Rule\Token(92, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionSubscription', [90,91,92,'SchemaDefinitionFieldValue',], '#Subscription'),
                94 => new \Railt\Compiler\Rule\Repetition(94, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition',94,], null),
                96 => new \Railt\Compiler\Rule\Repetition(96, 0, 1, 'Documentation', null),
                97 => new \Railt\Compiler\Rule\Token(97, 'T_SCALAR', null, -1, false),
                98 => new \Railt\Compiler\Rule\Repetition(98, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Railt\Compiler\Rule\Concatenation('ScalarDefinition', [96,97,'Name',98,], '#ScalarDefinition'),
                100 => new \Railt\Compiler\Rule\Repetition(100, 0, 1, 'Documentation', null),
                101 => new \Railt\Compiler\Rule\Token(101, 'T_INPUT', null, -1, false),
                102 => new \Railt\Compiler\Rule\Repetition(102, 0, -1, 'Directive', null),
                103 => new \Railt\Compiler\Rule\Token(103, 'T_BRACE_OPEN', null, -1, false),
                104 => new \Railt\Compiler\Rule\Repetition(104, 0, -1, 'InputDefinitionField', null),
                105 => new \Railt\Compiler\Rule\Token(105, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Railt\Compiler\Rule\Concatenation('InputDefinition', [100,101,'Name',102,103,104,105,], '#InputDefinition'),
                107 => new \Railt\Compiler\Rule\Repetition(107, 0, 1, 'Documentation', null),
                108 => new \Railt\Compiler\Rule\Token(108, 'T_COLON', null, -1, false),
                109 => new \Railt\Compiler\Rule\Repetition(109, 0, 1, 'InputDefinitionDefaultValue', null),
                110 => new \Railt\Compiler\Rule\Repetition(110, 0, -1, 'Directive', null),
                111 => new \Railt\Compiler\Rule\Concatenation(111, ['Key',108,'ValueDefinition',109,110,], null),
                'InputDefinitionField' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionField', [107,111,], '#Argument'),
                113 => new \Railt\Compiler\Rule\Token(113, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionDefaultValue', [113,'Value',], null),
                115 => new \Railt\Compiler\Rule\Repetition(115, 0, 1, 'Documentation', null),
                116 => new \Railt\Compiler\Rule\Token(116, 'T_EXTEND', null, -1, false),
                117 => new \Railt\Compiler\Rule\Concatenation(117, ['ObjectDefinition',], '#ExtendDefinition'),
                118 => new \Railt\Compiler\Rule\Concatenation(118, ['InterfaceDefinition',], '#ExtendDefinition'),
                119 => new \Railt\Compiler\Rule\Concatenation(119, ['EnumDefinition',], '#ExtendDefinition'),
                120 => new \Railt\Compiler\Rule\Concatenation(120, ['UnionDefinition',], '#ExtendDefinition'),
                121 => new \Railt\Compiler\Rule\Concatenation(121, ['SchemaDefinition',], '#ExtendDefinition'),
                122 => new \Railt\Compiler\Rule\Concatenation(122, ['ScalarDefinition',], '#ExtendDefinition'),
                123 => new \Railt\Compiler\Rule\Concatenation(123, ['InputDefinition',], '#ExtendDefinition'),
                124 => new \Railt\Compiler\Rule\Concatenation(124, ['DirectiveDefinition',], '#ExtendDefinition'),
                125 => new \Railt\Compiler\Rule\Choice(125, [117,118,119,120,121,122,123,124,], null),
                'ExtendDefinition' => new \Railt\Compiler\Rule\Concatenation('ExtendDefinition', [115,116,125,], null),
                127 => new \Railt\Compiler\Rule\Repetition(127, 0, 1, 'Documentation', null),
                128 => new \Railt\Compiler\Rule\Token(128, 'T_DIRECTIVE', null, -1, false),
                129 => new \Railt\Compiler\Rule\Token(129, 'T_DIRECTIVE_AT', null, -1, false),
                130 => new \Railt\Compiler\Rule\Repetition(130, 0, -1, 'DirectiveDefinitionArguments', null),
                131 => new \Railt\Compiler\Rule\Token(131, 'T_ON', null, -1, false),
                132 => new \Railt\Compiler\Rule\Repetition(132, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinition', [127,128,129,'Name',130,131,132,], '#DirectiveDefinition'),
                134 => new \Railt\Compiler\Rule\Token(134, 'T_PARENTHESIS_OPEN', null, -1, false),
                135 => new \Railt\Compiler\Rule\Repetition(135, 0, -1, 'DirectiveDefinitionArgument', null),
                136 => new \Railt\Compiler\Rule\Token(136, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArguments', [134,135,136,], null),
                138 => new \Railt\Compiler\Rule\Repetition(138, 0, 1, 'Documentation', null),
                139 => new \Railt\Compiler\Rule\Token(139, 'T_COLON', null, -1, false),
                140 => new \Railt\Compiler\Rule\Repetition(140, 0, 1, 'DirectiveDefinitionDefaultValue', null),
                141 => new \Railt\Compiler\Rule\Repetition(141, 0, -1, 'Directive', null),
                'DirectiveDefinitionArgument' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArgument', [138,'Key',139,'ValueDefinition',140,141,], '#Argument'),
                143 => new \Railt\Compiler\Rule\Token(143, 'T_OR', null, -1, false),
                144 => new \Railt\Compiler\Rule\Concatenation(144, [143,'Key',], null),
                145 => new \Railt\Compiler\Rule\Repetition(145, 0, -1, 144, null),
                'DirectiveDefinitionTargets' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionTargets', ['Key',145,], '#Target'),
                147 => new \Railt\Compiler\Rule\Token(147, 'T_EQUAL', null, -1, false),
                'DirectiveDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionDefaultValue', [147,'Value',], null),
                149 => new \Railt\Compiler\Rule\Repetition(149, 0, 1, 'Documentation', null),
                150 => new \Railt\Compiler\Rule\Token(150, 'T_TYPE', null, -1, false),
                151 => new \Railt\Compiler\Rule\Repetition(151, 0, 1, 'ObjectDefinitionImplements', null),
                152 => new \Railt\Compiler\Rule\Repetition(152, 0, -1, 'Directive', null),
                153 => new \Railt\Compiler\Rule\Token(153, 'T_BRACE_OPEN', null, -1, false),
                154 => new \Railt\Compiler\Rule\Repetition(154, 0, -1, 'ObjectDefinitionField', null),
                155 => new \Railt\Compiler\Rule\Token(155, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinition', [149,150,'Name',151,152,153,154,155,], '#ObjectDefinition'),
                157 => new \Railt\Compiler\Rule\Token(157, 'T_TYPE_IMPLEMENTS', null, -1, false),
                158 => new \Railt\Compiler\Rule\Repetition(158, 0, -1, 'Key', null),
                159 => new \Railt\Compiler\Rule\Token(159, 'T_AND', null, -1, false),
                160 => new \Railt\Compiler\Rule\Concatenation(160, [159,'Key',], null),
                161 => new \Railt\Compiler\Rule\Repetition(161, 0, 1, 160, null),
                'ObjectDefinitionImplements' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionImplements', [157,158,161,], '#Implements'),
                163 => new \Railt\Compiler\Rule\Repetition(163, 0, 1, 'Documentation', null),
                164 => new \Railt\Compiler\Rule\Repetition(164, 0, 1, 'Arguments', null),
                165 => new \Railt\Compiler\Rule\Token(165, 'T_COLON', null, -1, false),
                166 => new \Railt\Compiler\Rule\Concatenation(166, ['Key',164,165,'ObjectDefinitionFieldValue',], null),
                'ObjectDefinitionField' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionField', [163,166,], '#Field'),
                168 => new \Railt\Compiler\Rule\Repetition(168, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition',168,], null),
                170 => new \Railt\Compiler\Rule\Repetition(170, 0, 1, 'Documentation', null),
                171 => new \Railt\Compiler\Rule\Token(171, 'T_INTERFACE', null, -1, false),
                172 => new \Railt\Compiler\Rule\Repetition(172, 0, -1, 'Directive', null),
                173 => new \Railt\Compiler\Rule\Token(173, 'T_BRACE_OPEN', null, -1, false),
                174 => new \Railt\Compiler\Rule\Repetition(174, 0, -1, 'InterfaceDefinitionBody', null),
                175 => new \Railt\Compiler\Rule\Token(175, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinition', [170,171,'Name',172,173,174,175,], '#InterfaceDefinition'),
                177 => new \Railt\Compiler\Rule\Token(177, 'T_COLON', null, -1, false),
                178 => new \Railt\Compiler\Rule\Repetition(178, 0, -1, 'Directive', null),
                179 => new \Railt\Compiler\Rule\Concatenation(179, ['InterfaceDefinitionFieldKey',177,'ValueDefinition',178,], null),
                'InterfaceDefinitionBody' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionBody', [179,], '#Field'),
                181 => new \Railt\Compiler\Rule\Repetition(181, 0, 1, 'Documentation', null),
                182 => new \Railt\Compiler\Rule\Repetition(182, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionFieldKey', [181,'Key',182,], null),
                184 => new \Railt\Compiler\Rule\Repetition(184, 0, 1, 'Documentation', null),
                185 => new \Railt\Compiler\Rule\Token(185, 'T_ENUM', null, -1, false),
                186 => new \Railt\Compiler\Rule\Repetition(186, 0, -1, 'Directive', null),
                187 => new \Railt\Compiler\Rule\Token(187, 'T_BRACE_OPEN', null, -1, false),
                188 => new \Railt\Compiler\Rule\Repetition(188, 0, -1, 'EnumField', null),
                189 => new \Railt\Compiler\Rule\Token(189, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Railt\Compiler\Rule\Concatenation('EnumDefinition', [184,185,'Name',186,187,188,189,], '#EnumDefinition'),
                191 => new \Railt\Compiler\Rule\Repetition(191, 0, 1, 'Documentation', null),
                192 => new \Railt\Compiler\Rule\Repetition(192, 0, -1, 'Directive', null),
                193 => new \Railt\Compiler\Rule\Concatenation(193, ['EnumValue',192,], null),
                'EnumField' => new \Railt\Compiler\Rule\Concatenation('EnumField', [191,193,], '#Value'),
                195 => new \Railt\Compiler\Rule\Token(195, 'T_NAME', null, -1, true),
                196 => new \Railt\Compiler\Rule\Choice(196, [195,'Keyword',], null),
                'EnumValue' => new \Railt\Compiler\Rule\Concatenation('EnumValue', [196,], '#Name'),
                198 => new \Railt\Compiler\Rule\Repetition(198, 0, 1, 'Documentation', null),
                199 => new \Railt\Compiler\Rule\Token(199, 'T_UNION', null, -1, false),
                200 => new \Railt\Compiler\Rule\Repetition(200, 0, -1, 'Directive', null),
                201 => new \Railt\Compiler\Rule\Token(201, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Railt\Compiler\Rule\Concatenation('UnionDefinition', [198,199,'Name',200,201,'UnionBody',], '#UnionDefinition'),
                203 => new \Railt\Compiler\Rule\Token(203, 'T_OR', null, -1, false),
                204 => new \Railt\Compiler\Rule\Repetition(204, 0, 1, 203, null),
                205 => new \Railt\Compiler\Rule\Repetition(205, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Railt\Compiler\Rule\Concatenation('UnionBody', [204,205,], '#Relations'),
                207 => new \Railt\Compiler\Rule\Token(207, 'T_OR', null, -1, false),
                208 => new \Railt\Compiler\Rule\Concatenation(208, [207,'Name',], null),
                209 => new \Railt\Compiler\Rule\Repetition(209, 0, -1, 208, null),
                'UnionUnitesList' => new \Railt\Compiler\Rule\Concatenation('UnionUnitesList', ['Name',209,], null),
                211 => new \Railt\Compiler\Rule\Token(211, 'T_PARENTHESIS_OPEN', null, -1, false),
                212 => new \Railt\Compiler\Rule\Repetition(212, 0, -1, 'ArgumentPair', null),
                213 => new \Railt\Compiler\Rule\Token(213, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Railt\Compiler\Rule\Concatenation('Arguments', [211,212,213,], null),
                215 => new \Railt\Compiler\Rule\Repetition(215, 0, 1, 'Documentation', null),
                216 => new \Railt\Compiler\Rule\Token(216, 'T_COLON', null, -1, false),
                217 => new \Railt\Compiler\Rule\Repetition(217, 0, 1, 'ArgumentDefaultValue', null),
                218 => new \Railt\Compiler\Rule\Repetition(218, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Railt\Compiler\Rule\Concatenation('ArgumentPair', [215,'Key',216,'ValueDefinition',217,218,], '#Argument'),
                'ArgumentValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentValue', ['ValueDefinition',], '#Type'),
                221 => new \Railt\Compiler\Rule\Token(221, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentDefaultValue', [221,'Value',], null),
                223 => new \Railt\Compiler\Rule\Token(223, 'T_DIRECTIVE_AT', null, -1, false),
                224 => new \Railt\Compiler\Rule\Repetition(224, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Railt\Compiler\Rule\Concatenation('Directive', [223,'Name',224,], '#Directive'),
                226 => new \Railt\Compiler\Rule\Token(226, 'T_PARENTHESIS_OPEN', null, -1, false),
                227 => new \Railt\Compiler\Rule\Repetition(227, 0, -1, 'DirectiveArgumentPair', null),
                228 => new \Railt\Compiler\Rule\Token(228, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveArguments', [226,227,228,], null),
                230 => new \Railt\Compiler\Rule\Token(230, 'T_COLON', null, -1, false),
                'DirectiveArgumentPair' => new \Railt\Compiler\Rule\Concatenation('DirectiveArgumentPair', ['Key',230,'Value',], '#Argument'),],
            [
    'lexer.unicode' => true,
]        );

        
        $this->getRule('Document')->setDefaultId('#Document');
        $this->getRule('Document')->setPPRepresentation(' Directive()* Definition()*');
        $this->getRule('Definition')->setPPRepresentation(' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()');
        $this->getRule('ValueKeyword')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE> | <T_NULL>');
        $this->getRule('Keyword')->setPPRepresentation(' <T_ON> | <T_TYPE> | <T_TYPE_IMPLEMENTS> | <T_ENUM> | <T_UNION> | <T_INTERFACE> | <T_SCHEMA> | <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCALAR> | <T_DIRECTIVE> | <T_INPUT> | <T_EXTEND>');
        $this->getRule('Number')->setPPRepresentation(' <T_NUMBER_VALUE>');
        $this->getRule('Nullable')->setPPRepresentation(' <T_NULL>');
        $this->getRule('Boolean')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE>');
        $this->getRule('String')->setPPRepresentation(' (::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE::) | (::T_STRING_OPEN:: <T_STRING> ::T_STRING_CLOSE::)');
        $this->getRule('Word')->setPPRepresentation(' <T_NAME> | ValueKeyword()');
        $this->getRule('Name')->setPPRepresentation(' Word() #Name');
        $this->getRule('Key')->setPPRepresentation(' ( String() | Word() | Keyword() ) #Name');
        $this->getRule('Value')->setPPRepresentation(' ( String() | Number() | Nullable() | Keyword() | Object() | List() | Word() ) #Value');
        $this->getRule('ValueDefinition')->setPPRepresentation(' ValueDefinitionResolver()');
        $this->getRule('ValueDefinitionResolver')->setPPRepresentation(' (ValueListDefinition() <T_NON_NULL>? #List) | (ValueScalarDefinition() <T_NON_NULL>? #Type)');
        $this->getRule('ValueListDefinition')->setPPRepresentation(' ::T_BRACKET_OPEN:: (ValueScalarDefinition() <T_NON_NULL>? #Type) ::T_BRACKET_CLOSE::');
        $this->getRule('ValueScalarDefinition')->setPPRepresentation(' Keyword() | Word()');
        $this->getRule('Object')->setPPRepresentation(' ::T_BRACE_OPEN:: ObjectPair()* ::T_BRACE_CLOSE:: #Object');
        $this->getRule('ObjectPair')->setPPRepresentation(' Key() ::T_COLON:: Value() #ObjectPair');
        $this->getRule('List')->setPPRepresentation(' ::T_BRACKET_OPEN:: Value()* ::T_BRACKET_CLOSE:: #List');
        $this->getRule('Documentation')->setPPRepresentation(' ( ::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE:: ) #Description');
        $this->getRule('SchemaDefinition')->setDefaultId('#SchemaDefinition');
        $this->getRule('SchemaDefinition')->setPPRepresentation(' Documentation()? <T_SCHEMA> Name()? Directive()* ::T_BRACE_OPEN:: SchemaDefinitionBody() ::T_BRACE_CLOSE::');
        $this->getRule('SchemaDefinitionBody')->setPPRepresentation(' ( SchemaDefinitionQuery() | SchemaDefinitionMutation() | SchemaDefinitionSubscription() )*');
        $this->getRule('SchemaDefinitionQuery')->setPPRepresentation(' Documentation()? ::T_SCHEMA_QUERY:: ::T_COLON:: SchemaDefinitionFieldValue() #Query');
        $this->getRule('SchemaDefinitionMutation')->setPPRepresentation(' Documentation()? ::T_SCHEMA_MUTATION:: ::T_COLON:: SchemaDefinitionFieldValue() #Mutation');
        $this->getRule('SchemaDefinitionSubscription')->setPPRepresentation(' Documentation()? ::T_SCHEMA_SUBSCRIPTION:: ::T_COLON:: SchemaDefinitionFieldValue() #Subscription');
        $this->getRule('SchemaDefinitionFieldValue')->setPPRepresentation(' ValueDefinition() Directive()*');
        $this->getRule('ScalarDefinition')->setDefaultId('#ScalarDefinition');
        $this->getRule('ScalarDefinition')->setPPRepresentation(' Documentation()? ::T_SCALAR:: Name() Directive()*');
        $this->getRule('InputDefinition')->setDefaultId('#InputDefinition');
        $this->getRule('InputDefinition')->setPPRepresentation(' Documentation()? ::T_INPUT:: Name() Directive()* ::T_BRACE_OPEN:: InputDefinitionField()* ::T_BRACE_CLOSE::');
        $this->getRule('InputDefinitionField')->setPPRepresentation(' Documentation()? ( Key() ::T_COLON:: ValueDefinition() InputDefinitionDefaultValue()? Directive()* ) #Argument');
        $this->getRule('InputDefinitionDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
        $this->getRule('ExtendDefinition')->setDefaultId('#ExtendDefinition');
        $this->getRule('ExtendDefinition')->setPPRepresentation(' Documentation()? ::T_EXTEND:: ( ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | DirectiveDefinition() )');
        $this->getRule('DirectiveDefinition')->setDefaultId('#DirectiveDefinition');
        $this->getRule('DirectiveDefinition')->setPPRepresentation(' Documentation()? ::T_DIRECTIVE:: ::T_DIRECTIVE_AT:: Name() DirectiveDefinitionArguments()* ::T_ON:: DirectiveDefinitionTargets()+');
        $this->getRule('DirectiveDefinitionArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: DirectiveDefinitionArgument()* ::T_PARENTHESIS_CLOSE::');
        $this->getRule('DirectiveDefinitionArgument')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition() DirectiveDefinitionDefaultValue()? Directive()* #Argument');
        $this->getRule('DirectiveDefinitionTargets')->setPPRepresentation(' Key() (::T_OR:: Key())* #Target');
        $this->getRule('DirectiveDefinitionDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
        $this->getRule('ObjectDefinition')->setDefaultId('#ObjectDefinition');
        $this->getRule('ObjectDefinition')->setPPRepresentation(' Documentation()? ::T_TYPE:: Name() ObjectDefinitionImplements()? Directive()* ::T_BRACE_OPEN:: ObjectDefinitionField()* ::T_BRACE_CLOSE::');
        $this->getRule('ObjectDefinitionImplements')->setPPRepresentation(' ::T_TYPE_IMPLEMENTS:: Key()* ( ::T_AND:: Key() )? #Implements');
        $this->getRule('ObjectDefinitionField')->setPPRepresentation(' Documentation()? ( Key() Arguments()? ::T_COLON:: ObjectDefinitionFieldValue() ) #Field');
        $this->getRule('ObjectDefinitionFieldValue')->setPPRepresentation(' ValueDefinition() Directive()*');
        $this->getRule('InterfaceDefinition')->setDefaultId('#InterfaceDefinition');
        $this->getRule('InterfaceDefinition')->setPPRepresentation(' Documentation()? ::T_INTERFACE:: Name() Directive()* ::T_BRACE_OPEN:: InterfaceDefinitionBody()* ::T_BRACE_CLOSE::');
        $this->getRule('InterfaceDefinitionBody')->setPPRepresentation(' ( InterfaceDefinitionFieldKey() ::T_COLON:: ValueDefinition() Directive()* ) #Field');
        $this->getRule('InterfaceDefinitionFieldKey')->setPPRepresentation(' Documentation()? Key() Arguments()?');
        $this->getRule('EnumDefinition')->setDefaultId('#EnumDefinition');
        $this->getRule('EnumDefinition')->setPPRepresentation(' Documentation()? ::T_ENUM:: Name() Directive()* ::T_BRACE_OPEN:: EnumField()* ::T_BRACE_CLOSE::');
        $this->getRule('EnumField')->setPPRepresentation(' Documentation()? ( EnumValue() Directive()* ) #Value');
        $this->getRule('EnumValue')->setPPRepresentation(' ( <T_NAME> | Keyword() ) #Name');
        $this->getRule('UnionDefinition')->setDefaultId('#UnionDefinition');
        $this->getRule('UnionDefinition')->setPPRepresentation(' Documentation()? ::T_UNION:: Name() Directive()* ::T_EQUAL:: UnionBody()');
        $this->getRule('UnionBody')->setPPRepresentation(' ::T_OR::? UnionUnitesList()+ #Relations');
        $this->getRule('UnionUnitesList')->setPPRepresentation(' Name() (::T_OR:: Name())*');
        $this->getRule('Arguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: ArgumentPair()* ::T_PARENTHESIS_CLOSE::');
        $this->getRule('ArgumentPair')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition() ArgumentDefaultValue()? Directive()* #Argument');
        $this->getRule('ArgumentValue')->setPPRepresentation(' ValueDefinition() #Type');
        $this->getRule('ArgumentDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
        $this->getRule('Directive')->setDefaultId('#Directive');
        $this->getRule('Directive')->setPPRepresentation(' ::T_DIRECTIVE_AT:: Name() DirectiveArguments()?');
        $this->getRule('DirectiveArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: DirectiveArgumentPair()* ::T_PARENTHESIS_CLOSE::');
        $this->getRule('DirectiveArgumentPair')->setPPRepresentation(' Key() ::T_COLON:: Value() #Argument');
    }
}
