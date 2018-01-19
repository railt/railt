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
 * Generated at 19-01-2018 18:02:22
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
            null,
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
        'T_MULTILINE_STRING' => [
            '"""(?:\\\\"""|(?!""").|\\s)+"""',
            null,
            true,
        ],
        'T_STRING' => [
            '"[^"\\\\]+(\\\\.[^"\\\\]*)*"',
            null,
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
                27 => new \Railt\Compiler\Rule\Token(27, 'T_MULTILINE_STRING', null, -1, true),
                28 => new \Railt\Compiler\Rule\Token(28, 'T_STRING', null, -1, true),
                'String' => new \Railt\Compiler\Rule\Choice('String', [27,28,], null),
                30 => new \Railt\Compiler\Rule\Token(30, 'T_NAME', null, -1, true),
                'Word' => new \Railt\Compiler\Rule\Choice('Word', [30,'ValueKeyword',], null),
                'Name' => new \Railt\Compiler\Rule\Concatenation('Name', ['Word',], '#Name'),
                33 => new \Railt\Compiler\Rule\Choice(33, ['String','Word','Keyword',], null),
                'Key' => new \Railt\Compiler\Rule\Concatenation('Key', [33,], '#Name'),
                35 => new \Railt\Compiler\Rule\Choice(35, ['String','Number','Nullable','Keyword','Object','List','Word',], null),
                'Value' => new \Railt\Compiler\Rule\Concatenation('Value', [35,], '#Value'),
                'ValueDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver',], null),
                38 => new \Railt\Compiler\Rule\Token(38, 'T_NON_NULL', null, -1, true),
                39 => new \Railt\Compiler\Rule\Repetition(39, 0, 1, 38, null),
                40 => new \Railt\Compiler\Rule\Concatenation(40, ['ValueListDefinition',39,], '#List'),
                41 => new \Railt\Compiler\Rule\Token(41, 'T_NON_NULL', null, -1, true),
                42 => new \Railt\Compiler\Rule\Repetition(42, 0, 1, 41, null),
                43 => new \Railt\Compiler\Rule\Concatenation(43, ['ValueScalarDefinition',42,], '#Type'),
                'ValueDefinitionResolver' => new \Railt\Compiler\Rule\Choice('ValueDefinitionResolver', [40,43,], null),
                45 => new \Railt\Compiler\Rule\Token(45, 'T_BRACKET_OPEN', null, -1, false),
                46 => new \Railt\Compiler\Rule\Token(46, 'T_NON_NULL', null, -1, true),
                47 => new \Railt\Compiler\Rule\Repetition(47, 0, 1, 46, null),
                48 => new \Railt\Compiler\Rule\Concatenation(48, ['ValueScalarDefinition',47,], '#Type'),
                49 => new \Railt\Compiler\Rule\Token(49, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueListDefinition', [45,48,49,], null),
                'ValueScalarDefinition' => new \Railt\Compiler\Rule\Choice('ValueScalarDefinition', ['Keyword','Word',], null),
                52 => new \Railt\Compiler\Rule\Token(52, 'T_BRACE_OPEN', null, -1, false),
                53 => new \Railt\Compiler\Rule\Repetition(53, 0, -1, 'ObjectPair', null),
                54 => new \Railt\Compiler\Rule\Token(54, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Railt\Compiler\Rule\Concatenation('Object', [52,53,54,], '#Object'),
                56 => new \Railt\Compiler\Rule\Token(56, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Railt\Compiler\Rule\Concatenation('ObjectPair', ['Key',56,'Value',], '#ObjectPair'),
                58 => new \Railt\Compiler\Rule\Token(58, 'T_BRACKET_OPEN', null, -1, false),
                59 => new \Railt\Compiler\Rule\Repetition(59, 0, -1, 'Value', null),
                60 => new \Railt\Compiler\Rule\Token(60, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Railt\Compiler\Rule\Concatenation('List', [58,59,60,], '#List'),
                62 => new \Railt\Compiler\Rule\Token(62, 'T_MULTILINE_STRING', null, -1, true),
                'Documentation' => new \Railt\Compiler\Rule\Concatenation('Documentation', [62,], '#Description'),
                64 => new \Railt\Compiler\Rule\Repetition(64, 0, 1, 'Documentation', null),
                65 => new \Railt\Compiler\Rule\Token(65, 'T_SCHEMA', null, -1, true),
                66 => new \Railt\Compiler\Rule\Repetition(66, 0, 1, 'Name', null),
                67 => new \Railt\Compiler\Rule\Repetition(67, 0, -1, 'Directive', null),
                68 => new \Railt\Compiler\Rule\Token(68, 'T_BRACE_OPEN', null, -1, false),
                69 => new \Railt\Compiler\Rule\Token(69, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinition', [64,65,66,67,68,'SchemaDefinitionBody',69,], '#SchemaDefinition'),
                71 => new \Railt\Compiler\Rule\Choice(71, ['SchemaDefinitionQuery','SchemaDefinitionMutation','SchemaDefinitionSubscription',], null),
                'SchemaDefinitionBody' => new \Railt\Compiler\Rule\Repetition('SchemaDefinitionBody', 0, -1, 71, null),
                73 => new \Railt\Compiler\Rule\Repetition(73, 0, 1, 'Documentation', null),
                74 => new \Railt\Compiler\Rule\Token(74, 'T_SCHEMA_QUERY', null, -1, false),
                75 => new \Railt\Compiler\Rule\Token(75, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionQuery', [73,74,75,'SchemaDefinitionFieldValue',], '#Query'),
                77 => new \Railt\Compiler\Rule\Repetition(77, 0, 1, 'Documentation', null),
                78 => new \Railt\Compiler\Rule\Token(78, 'T_SCHEMA_MUTATION', null, -1, false),
                79 => new \Railt\Compiler\Rule\Token(79, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionMutation', [77,78,79,'SchemaDefinitionFieldValue',], '#Mutation'),
                81 => new \Railt\Compiler\Rule\Repetition(81, 0, 1, 'Documentation', null),
                82 => new \Railt\Compiler\Rule\Token(82, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                83 => new \Railt\Compiler\Rule\Token(83, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionSubscription', [81,82,83,'SchemaDefinitionFieldValue',], '#Subscription'),
                85 => new \Railt\Compiler\Rule\Repetition(85, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition',85,], null),
                87 => new \Railt\Compiler\Rule\Repetition(87, 0, 1, 'Documentation', null),
                88 => new \Railt\Compiler\Rule\Token(88, 'T_SCALAR', null, -1, false),
                89 => new \Railt\Compiler\Rule\Repetition(89, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Railt\Compiler\Rule\Concatenation('ScalarDefinition', [87,88,'Name',89,], '#ScalarDefinition'),
                91 => new \Railt\Compiler\Rule\Repetition(91, 0, 1, 'Documentation', null),
                92 => new \Railt\Compiler\Rule\Token(92, 'T_INPUT', null, -1, false),
                93 => new \Railt\Compiler\Rule\Repetition(93, 0, -1, 'Directive', null),
                94 => new \Railt\Compiler\Rule\Token(94, 'T_BRACE_OPEN', null, -1, false),
                95 => new \Railt\Compiler\Rule\Repetition(95, 0, -1, 'InputDefinitionField', null),
                96 => new \Railt\Compiler\Rule\Token(96, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Railt\Compiler\Rule\Concatenation('InputDefinition', [91,92,'Name',93,94,95,96,], '#InputDefinition'),
                98 => new \Railt\Compiler\Rule\Repetition(98, 0, 1, 'Documentation', null),
                99 => new \Railt\Compiler\Rule\Token(99, 'T_COLON', null, -1, false),
                100 => new \Railt\Compiler\Rule\Repetition(100, 0, 1, 'InputDefinitionDefaultValue', null),
                101 => new \Railt\Compiler\Rule\Repetition(101, 0, -1, 'Directive', null),
                102 => new \Railt\Compiler\Rule\Concatenation(102, ['Key',99,'ValueDefinition',100,101,], null),
                'InputDefinitionField' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionField', [98,102,], '#Argument'),
                104 => new \Railt\Compiler\Rule\Token(104, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionDefaultValue', [104,'Value',], null),
                106 => new \Railt\Compiler\Rule\Repetition(106, 0, 1, 'Documentation', null),
                107 => new \Railt\Compiler\Rule\Token(107, 'T_EXTEND', null, -1, false),
                108 => new \Railt\Compiler\Rule\Concatenation(108, ['ObjectDefinition',], '#ExtendDefinition'),
                109 => new \Railt\Compiler\Rule\Concatenation(109, ['InterfaceDefinition',], '#ExtendDefinition'),
                110 => new \Railt\Compiler\Rule\Concatenation(110, ['EnumDefinition',], '#ExtendDefinition'),
                111 => new \Railt\Compiler\Rule\Concatenation(111, ['UnionDefinition',], '#ExtendDefinition'),
                112 => new \Railt\Compiler\Rule\Concatenation(112, ['SchemaDefinition',], '#ExtendDefinition'),
                113 => new \Railt\Compiler\Rule\Concatenation(113, ['ScalarDefinition',], '#ExtendDefinition'),
                114 => new \Railt\Compiler\Rule\Concatenation(114, ['InputDefinition',], '#ExtendDefinition'),
                115 => new \Railt\Compiler\Rule\Concatenation(115, ['DirectiveDefinition',], '#ExtendDefinition'),
                116 => new \Railt\Compiler\Rule\Choice(116, [108,109,110,111,112,113,114,115,], null),
                'ExtendDefinition' => new \Railt\Compiler\Rule\Concatenation('ExtendDefinition', [106,107,116,], null),
                118 => new \Railt\Compiler\Rule\Repetition(118, 0, 1, 'Documentation', null),
                119 => new \Railt\Compiler\Rule\Token(119, 'T_DIRECTIVE', null, -1, false),
                120 => new \Railt\Compiler\Rule\Token(120, 'T_DIRECTIVE_AT', null, -1, false),
                121 => new \Railt\Compiler\Rule\Repetition(121, 0, -1, 'DirectiveDefinitionArguments', null),
                122 => new \Railt\Compiler\Rule\Token(122, 'T_ON', null, -1, false),
                123 => new \Railt\Compiler\Rule\Repetition(123, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinition', [118,119,120,'Name',121,122,123,], '#DirectiveDefinition'),
                125 => new \Railt\Compiler\Rule\Token(125, 'T_PARENTHESIS_OPEN', null, -1, false),
                126 => new \Railt\Compiler\Rule\Repetition(126, 0, -1, 'DirectiveDefinitionArgument', null),
                127 => new \Railt\Compiler\Rule\Token(127, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArguments', [125,126,127,], null),
                129 => new \Railt\Compiler\Rule\Repetition(129, 0, 1, 'Documentation', null),
                130 => new \Railt\Compiler\Rule\Token(130, 'T_COLON', null, -1, false),
                131 => new \Railt\Compiler\Rule\Repetition(131, 0, 1, 'DirectiveDefinitionDefaultValue', null),
                132 => new \Railt\Compiler\Rule\Repetition(132, 0, -1, 'Directive', null),
                'DirectiveDefinitionArgument' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArgument', [129,'Key',130,'ValueDefinition',131,132,], '#Argument'),
                134 => new \Railt\Compiler\Rule\Token(134, 'T_OR', null, -1, false),
                135 => new \Railt\Compiler\Rule\Concatenation(135, [134,'Key',], null),
                136 => new \Railt\Compiler\Rule\Repetition(136, 0, -1, 135, null),
                'DirectiveDefinitionTargets' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionTargets', ['Key',136,], '#Target'),
                138 => new \Railt\Compiler\Rule\Token(138, 'T_EQUAL', null, -1, false),
                'DirectiveDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionDefaultValue', [138,'Value',], null),
                140 => new \Railt\Compiler\Rule\Repetition(140, 0, 1, 'Documentation', null),
                141 => new \Railt\Compiler\Rule\Token(141, 'T_TYPE', null, -1, false),
                142 => new \Railt\Compiler\Rule\Repetition(142, 0, 1, 'ObjectDefinitionImplements', null),
                143 => new \Railt\Compiler\Rule\Repetition(143, 0, -1, 'Directive', null),
                144 => new \Railt\Compiler\Rule\Token(144, 'T_BRACE_OPEN', null, -1, false),
                145 => new \Railt\Compiler\Rule\Repetition(145, 0, -1, 'ObjectDefinitionField', null),
                146 => new \Railt\Compiler\Rule\Token(146, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinition', [140,141,'Name',142,143,144,145,146,], '#ObjectDefinition'),
                148 => new \Railt\Compiler\Rule\Token(148, 'T_TYPE_IMPLEMENTS', null, -1, false),
                149 => new \Railt\Compiler\Rule\Repetition(149, 0, -1, 'Key', null),
                150 => new \Railt\Compiler\Rule\Token(150, 'T_AND', null, -1, false),
                151 => new \Railt\Compiler\Rule\Concatenation(151, [150,'Key',], null),
                152 => new \Railt\Compiler\Rule\Repetition(152, 0, 1, 151, null),
                'ObjectDefinitionImplements' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionImplements', [148,149,152,], '#Implements'),
                154 => new \Railt\Compiler\Rule\Repetition(154, 0, 1, 'Documentation', null),
                155 => new \Railt\Compiler\Rule\Repetition(155, 0, 1, 'Arguments', null),
                156 => new \Railt\Compiler\Rule\Token(156, 'T_COLON', null, -1, false),
                157 => new \Railt\Compiler\Rule\Concatenation(157, ['Key',155,156,'ObjectDefinitionFieldValue',], null),
                'ObjectDefinitionField' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionField', [154,157,], '#Field'),
                159 => new \Railt\Compiler\Rule\Repetition(159, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition',159,], null),
                161 => new \Railt\Compiler\Rule\Repetition(161, 0, 1, 'Documentation', null),
                162 => new \Railt\Compiler\Rule\Token(162, 'T_INTERFACE', null, -1, false),
                163 => new \Railt\Compiler\Rule\Repetition(163, 0, -1, 'Directive', null),
                164 => new \Railt\Compiler\Rule\Token(164, 'T_BRACE_OPEN', null, -1, false),
                165 => new \Railt\Compiler\Rule\Repetition(165, 0, -1, 'InterfaceDefinitionBody', null),
                166 => new \Railt\Compiler\Rule\Token(166, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinition', [161,162,'Name',163,164,165,166,], '#InterfaceDefinition'),
                168 => new \Railt\Compiler\Rule\Token(168, 'T_COLON', null, -1, false),
                169 => new \Railt\Compiler\Rule\Repetition(169, 0, -1, 'Directive', null),
                170 => new \Railt\Compiler\Rule\Concatenation(170, ['InterfaceDefinitionFieldKey',168,'ValueDefinition',169,], null),
                'InterfaceDefinitionBody' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionBody', [170,], '#Field'),
                172 => new \Railt\Compiler\Rule\Repetition(172, 0, 1, 'Documentation', null),
                173 => new \Railt\Compiler\Rule\Repetition(173, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionFieldKey', [172,'Key',173,], null),
                175 => new \Railt\Compiler\Rule\Repetition(175, 0, 1, 'Documentation', null),
                176 => new \Railt\Compiler\Rule\Token(176, 'T_ENUM', null, -1, false),
                177 => new \Railt\Compiler\Rule\Repetition(177, 0, -1, 'Directive', null),
                178 => new \Railt\Compiler\Rule\Token(178, 'T_BRACE_OPEN', null, -1, false),
                179 => new \Railt\Compiler\Rule\Repetition(179, 0, -1, 'EnumField', null),
                180 => new \Railt\Compiler\Rule\Token(180, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Railt\Compiler\Rule\Concatenation('EnumDefinition', [175,176,'Name',177,178,179,180,], '#EnumDefinition'),
                182 => new \Railt\Compiler\Rule\Repetition(182, 0, 1, 'Documentation', null),
                183 => new \Railt\Compiler\Rule\Repetition(183, 0, -1, 'Directive', null),
                184 => new \Railt\Compiler\Rule\Concatenation(184, ['EnumValue',183,], null),
                'EnumField' => new \Railt\Compiler\Rule\Concatenation('EnumField', [182,184,], '#Value'),
                186 => new \Railt\Compiler\Rule\Token(186, 'T_NAME', null, -1, true),
                187 => new \Railt\Compiler\Rule\Choice(187, [186,'Keyword',], null),
                'EnumValue' => new \Railt\Compiler\Rule\Concatenation('EnumValue', [187,], '#Name'),
                189 => new \Railt\Compiler\Rule\Repetition(189, 0, 1, 'Documentation', null),
                190 => new \Railt\Compiler\Rule\Token(190, 'T_UNION', null, -1, false),
                191 => new \Railt\Compiler\Rule\Repetition(191, 0, -1, 'Directive', null),
                192 => new \Railt\Compiler\Rule\Token(192, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Railt\Compiler\Rule\Concatenation('UnionDefinition', [189,190,'Name',191,192,'UnionBody',], '#UnionDefinition'),
                194 => new \Railt\Compiler\Rule\Token(194, 'T_OR', null, -1, false),
                195 => new \Railt\Compiler\Rule\Repetition(195, 0, 1, 194, null),
                196 => new \Railt\Compiler\Rule\Repetition(196, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Railt\Compiler\Rule\Concatenation('UnionBody', [195,196,], '#Relations'),
                198 => new \Railt\Compiler\Rule\Token(198, 'T_OR', null, -1, false),
                199 => new \Railt\Compiler\Rule\Concatenation(199, [198,'Name',], null),
                200 => new \Railt\Compiler\Rule\Repetition(200, 0, -1, 199, null),
                'UnionUnitesList' => new \Railt\Compiler\Rule\Concatenation('UnionUnitesList', ['Name',200,], null),
                202 => new \Railt\Compiler\Rule\Token(202, 'T_PARENTHESIS_OPEN', null, -1, false),
                203 => new \Railt\Compiler\Rule\Repetition(203, 0, -1, 'ArgumentPair', null),
                204 => new \Railt\Compiler\Rule\Token(204, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Railt\Compiler\Rule\Concatenation('Arguments', [202,203,204,], null),
                206 => new \Railt\Compiler\Rule\Repetition(206, 0, 1, 'Documentation', null),
                207 => new \Railt\Compiler\Rule\Token(207, 'T_COLON', null, -1, false),
                208 => new \Railt\Compiler\Rule\Repetition(208, 0, 1, 'ArgumentDefaultValue', null),
                209 => new \Railt\Compiler\Rule\Repetition(209, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Railt\Compiler\Rule\Concatenation('ArgumentPair', [206,'Key',207,'ValueDefinition',208,209,], '#Argument'),
                'ArgumentValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentValue', ['ValueDefinition',], '#Type'),
                212 => new \Railt\Compiler\Rule\Token(212, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentDefaultValue', [212,'Value',], null),
                214 => new \Railt\Compiler\Rule\Token(214, 'T_DIRECTIVE_AT', null, -1, false),
                215 => new \Railt\Compiler\Rule\Repetition(215, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Railt\Compiler\Rule\Concatenation('Directive', [214,'Name',215,], '#Directive'),
                217 => new \Railt\Compiler\Rule\Token(217, 'T_PARENTHESIS_OPEN', null, -1, false),
                218 => new \Railt\Compiler\Rule\Repetition(218, 0, -1, 'DirectiveArgumentPair', null),
                219 => new \Railt\Compiler\Rule\Token(219, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveArguments', [217,218,219,], null),
                221 => new \Railt\Compiler\Rule\Token(221, 'T_COLON', null, -1, false),
                'DirectiveArgumentPair' => new \Railt\Compiler\Rule\Concatenation('DirectiveArgumentPair', ['Key',221,'Value',], '#Argument'),],
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
        $this->getRule('String')->setPPRepresentation(' <T_MULTILINE_STRING> | <T_STRING>');
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
        $this->getRule('Documentation')->setPPRepresentation(' <T_MULTILINE_STRING> #Description');
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
