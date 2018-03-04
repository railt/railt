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
 * Generated at 04-03-2018 10:15:34
 */
class Compiled extends \Railt\Compiler\Parser
{
    public function __construct()
    {
        parent::__construct(
            [
    'T_NON_NULL' => [
        '!',
        true,
    ],
    'T_VAR' => [
        '\\$',
        true,
    ],
    'T_PARENTHESIS_OPEN' => [
        '\\(',
        true,
    ],
    'T_PARENTHESIS_CLOSE' => [
        '\\)',
        true,
    ],
    'T_THREE_DOTS' => [
        '\\.\\.\\.',
        true,
    ],
    'T_COLON' => [
        ':',
        true,
    ],
    'T_EQUAL' => [
        '=',
        true,
    ],
    'T_DIRECTIVE_AT' => [
        '@',
        true,
    ],
    'T_BRACKET_OPEN' => [
        '\\[',
        true,
    ],
    'T_BRACKET_CLOSE' => [
        '\\]',
        true,
    ],
    'T_BRACE_OPEN' => [
        '{',
        true,
    ],
    'T_BRACE_CLOSE' => [
        '}',
        true,
    ],
    'T_OR' => [
        '\\|',
        true,
    ],
    'T_AND' => [
        '\\&',
        true,
    ],
    'T_ON' => [
        'on\\b',
        true,
    ],
    'T_NUMBER_VALUE' => [
        '\\-?(0|[1-9][0-9]*)(\\.[0-9]+)?([eE][\\+\\-]?[0-9]+)?\\b',
        true,
    ],
    'T_BOOL_TRUE' => [
        'true\\b',
        true,
    ],
    'T_BOOL_FALSE' => [
        'false\\b',
        true,
    ],
    'T_NULL' => [
        'null\\b',
        true,
    ],
    'T_MULTILINE_STRING' => [
        '"""(?:\\\\"""|(?!""").|\\s)*"""',
        true,
    ],
    'T_STRING' => [
        '"[^"\\\\]*(\\\\.[^"\\\\]*)*"',
        true,
    ],
    'T_TYPE' => [
        'type\\b',
        true,
    ],
    'T_TYPE_IMPLEMENTS' => [
        'implements\\b',
        true,
    ],
    'T_ENUM' => [
        'enum\\b',
        true,
    ],
    'T_UNION' => [
        'union\\b',
        true,
    ],
    'T_INTERFACE' => [
        'interface\\b',
        true,
    ],
    'T_SCHEMA' => [
        'schema\\b',
        true,
    ],
    'T_SCHEMA_QUERY' => [
        'query\\b',
        true,
    ],
    'T_SCHEMA_MUTATION' => [
        'mutation\\b',
        true,
    ],
    'T_SCHEMA_SUBSCRIPTION' => [
        'subscription\\b',
        true,
    ],
    'T_SCALAR' => [
        'scalar\\b',
        true,
    ],
    'T_DIRECTIVE' => [
        'directive\\b',
        true,
    ],
    'T_INPUT' => [
        'input\\b',
        true,
    ],
    'T_EXTEND' => [
        'extend\\b',
        true,
    ],
    'T_NAME' => [
        '([_A-Za-z][_0-9A-Za-z]*)',
        true,
    ],
    'T_WHITESPACE' => [
        '[\\xfe\\xff|\\x20|\\x09|\\x0a|\\x0d]+',
        false,
    ],
    'T_COMMENT' => [
        '#[^\\n]*',
        false,
    ],
    'T_COMMA' => [
        ',',
        false,
    ],
],
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
                17 => new \Railt\Compiler\Rule\Token(17, 'T_SCHEMA_SUBSCRIPTION', null, -1, true),
                18 => new \Railt\Compiler\Rule\Token(18, 'T_SCALAR', null, -1, true),
                19 => new \Railt\Compiler\Rule\Token(19, 'T_DIRECTIVE', null, -1, true),
                20 => new \Railt\Compiler\Rule\Token(20, 'T_INPUT', null, -1, true),
                21 => new \Railt\Compiler\Rule\Token(21, 'T_EXTEND', null, -1, true),
                'Keyword' => new \Railt\Compiler\Rule\Choice('Keyword', [8,9,10,11,12,13,14,15,16,17,18,19,20,21,], null),
                'Number' => new \Railt\Compiler\Rule\Token('Number', 'T_NUMBER_VALUE', null, -1, true),
                'Nullable' => new \Railt\Compiler\Rule\Token('Nullable', 'T_NULL', null, -1, true),
                25 => new \Railt\Compiler\Rule\Token(25, 'T_BOOL_TRUE', null, -1, true),
                26 => new \Railt\Compiler\Rule\Token(26, 'T_BOOL_FALSE', null, -1, true),
                'Boolean' => new \Railt\Compiler\Rule\Choice('Boolean', [25,26,], null),
                28 => new \Railt\Compiler\Rule\Token(28, 'T_MULTILINE_STRING', null, -1, true),
                29 => new \Railt\Compiler\Rule\Token(29, 'T_STRING', null, -1, true),
                'String' => new \Railt\Compiler\Rule\Choice('String', [28,29,], null),
                31 => new \Railt\Compiler\Rule\Token(31, 'T_NAME', null, -1, true),
                'Word' => new \Railt\Compiler\Rule\Choice('Word', [31,'ValueKeyword',], null),
                33 => new \Railt\Compiler\Rule\Token(33, 'T_SCHEMA_QUERY', null, -1, true),
                34 => new \Railt\Compiler\Rule\Concatenation(34, [33,], '#Name'),
                35 => new \Railt\Compiler\Rule\Token(35, 'T_SCHEMA_MUTATION', null, -1, true),
                36 => new \Railt\Compiler\Rule\Concatenation(36, [35,], '#Name'),
                37 => new \Railt\Compiler\Rule\Token(37, 'T_SCHEMA_SUBSCRIPTION', null, -1, true),
                38 => new \Railt\Compiler\Rule\Concatenation(38, [37,], '#Name'),
                39 => new \Railt\Compiler\Rule\Concatenation(39, ['Word',], '#Name'),
                'Name' => new \Railt\Compiler\Rule\Choice('Name', [34,36,38,39,], null),
                41 => new \Railt\Compiler\Rule\Choice(41, ['String','Word','Keyword',], null),
                'Key' => new \Railt\Compiler\Rule\Concatenation('Key', [41,], '#Name'),
                43 => new \Railt\Compiler\Rule\Choice(43, ['String','Number','Nullable','Keyword','Object','List','Word',], null),
                'Value' => new \Railt\Compiler\Rule\Concatenation('Value', [43,], '#Value'),
                'ValueDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver',], null),
                46 => new \Railt\Compiler\Rule\Token(46, 'T_NON_NULL', null, -1, true),
                47 => new \Railt\Compiler\Rule\Repetition(47, 0, 1, 46, null),
                48 => new \Railt\Compiler\Rule\Concatenation(48, ['ValueListDefinition',47,], '#List'),
                49 => new \Railt\Compiler\Rule\Token(49, 'T_NON_NULL', null, -1, true),
                50 => new \Railt\Compiler\Rule\Repetition(50, 0, 1, 49, null),
                51 => new \Railt\Compiler\Rule\Concatenation(51, ['ValueScalarDefinition',50,], '#Type'),
                'ValueDefinitionResolver' => new \Railt\Compiler\Rule\Choice('ValueDefinitionResolver', [48,51,], null),
                53 => new \Railt\Compiler\Rule\Token(53, 'T_BRACKET_OPEN', null, -1, false),
                54 => new \Railt\Compiler\Rule\Token(54, 'T_NON_NULL', null, -1, true),
                55 => new \Railt\Compiler\Rule\Repetition(55, 0, 1, 54, null),
                56 => new \Railt\Compiler\Rule\Concatenation(56, ['ValueScalarDefinition',55,], '#Type'),
                57 => new \Railt\Compiler\Rule\Token(57, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Railt\Compiler\Rule\Concatenation('ValueListDefinition', [53,56,57,], null),
                'ValueScalarDefinition' => new \Railt\Compiler\Rule\Choice('ValueScalarDefinition', ['Keyword','Word',], null),
                60 => new \Railt\Compiler\Rule\Token(60, 'T_BRACE_OPEN', null, -1, false),
                61 => new \Railt\Compiler\Rule\Repetition(61, 0, -1, 'ObjectPair', null),
                62 => new \Railt\Compiler\Rule\Token(62, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Railt\Compiler\Rule\Concatenation('Object', [60,61,62,], '#Object'),
                64 => new \Railt\Compiler\Rule\Token(64, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Railt\Compiler\Rule\Concatenation('ObjectPair', ['Key',64,'Value',], '#ObjectPair'),
                66 => new \Railt\Compiler\Rule\Token(66, 'T_BRACKET_OPEN', null, -1, false),
                67 => new \Railt\Compiler\Rule\Repetition(67, 0, -1, 'Value', null),
                68 => new \Railt\Compiler\Rule\Token(68, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Railt\Compiler\Rule\Concatenation('List', [66,67,68,], '#List'),
                70 => new \Railt\Compiler\Rule\Token(70, 'T_MULTILINE_STRING', null, -1, true),
                'Documentation' => new \Railt\Compiler\Rule\Concatenation('Documentation', [70,], '#Description'),
                72 => new \Railt\Compiler\Rule\Repetition(72, 0, 1, 'Documentation', null),
                73 => new \Railt\Compiler\Rule\Token(73, 'T_SCHEMA', null, -1, true),
                74 => new \Railt\Compiler\Rule\Repetition(74, 0, 1, 'Name', null),
                75 => new \Railt\Compiler\Rule\Repetition(75, 0, -1, 'Directive', null),
                76 => new \Railt\Compiler\Rule\Token(76, 'T_BRACE_OPEN', null, -1, false),
                77 => new \Railt\Compiler\Rule\Token(77, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinition', [72,73,74,75,76,'SchemaDefinitionBody',77,], '#SchemaDefinition'),
                79 => new \Railt\Compiler\Rule\Choice(79, ['SchemaDefinitionQuery','SchemaDefinitionMutation','SchemaDefinitionSubscription',], null),
                'SchemaDefinitionBody' => new \Railt\Compiler\Rule\Repetition('SchemaDefinitionBody', 0, -1, 79, null),
                81 => new \Railt\Compiler\Rule\Repetition(81, 0, 1, 'Documentation', null),
                82 => new \Railt\Compiler\Rule\Token(82, 'T_SCHEMA_QUERY', null, -1, false),
                83 => new \Railt\Compiler\Rule\Token(83, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionQuery', [81,82,83,'SchemaDefinitionFieldValue',], '#Query'),
                85 => new \Railt\Compiler\Rule\Repetition(85, 0, 1, 'Documentation', null),
                86 => new \Railt\Compiler\Rule\Token(86, 'T_SCHEMA_MUTATION', null, -1, false),
                87 => new \Railt\Compiler\Rule\Token(87, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionMutation', [85,86,87,'SchemaDefinitionFieldValue',], '#Mutation'),
                89 => new \Railt\Compiler\Rule\Repetition(89, 0, 1, 'Documentation', null),
                90 => new \Railt\Compiler\Rule\Token(90, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                91 => new \Railt\Compiler\Rule\Token(91, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionSubscription', [89,90,91,'SchemaDefinitionFieldValue',], '#Subscription'),
                93 => new \Railt\Compiler\Rule\Repetition(93, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition',93,], null),
                95 => new \Railt\Compiler\Rule\Repetition(95, 0, 1, 'Documentation', null),
                96 => new \Railt\Compiler\Rule\Token(96, 'T_SCALAR', null, -1, false),
                97 => new \Railt\Compiler\Rule\Repetition(97, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Railt\Compiler\Rule\Concatenation('ScalarDefinition', [95,96,'Name',97,], '#ScalarDefinition'),
                99 => new \Railt\Compiler\Rule\Repetition(99, 0, 1, 'Documentation', null),
                100 => new \Railt\Compiler\Rule\Token(100, 'T_INPUT', null, -1, false),
                101 => new \Railt\Compiler\Rule\Repetition(101, 0, -1, 'Directive', null),
                102 => new \Railt\Compiler\Rule\Token(102, 'T_BRACE_OPEN', null, -1, false),
                103 => new \Railt\Compiler\Rule\Repetition(103, 0, -1, 'InputDefinitionField', null),
                104 => new \Railt\Compiler\Rule\Token(104, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Railt\Compiler\Rule\Concatenation('InputDefinition', [99,100,'Name',101,102,103,104,], '#InputDefinition'),
                106 => new \Railt\Compiler\Rule\Repetition(106, 0, 1, 'Documentation', null),
                107 => new \Railt\Compiler\Rule\Token(107, 'T_COLON', null, -1, false),
                108 => new \Railt\Compiler\Rule\Repetition(108, 0, 1, 'InputDefinitionDefaultValue', null),
                109 => new \Railt\Compiler\Rule\Repetition(109, 0, -1, 'Directive', null),
                110 => new \Railt\Compiler\Rule\Concatenation(110, ['Key',107,'ValueDefinition',108,109,], null),
                'InputDefinitionField' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionField', [106,110,], '#Argument'),
                112 => new \Railt\Compiler\Rule\Token(112, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('InputDefinitionDefaultValue', [112,'Value',], null),
                114 => new \Railt\Compiler\Rule\Repetition(114, 0, 1, 'Documentation', null),
                115 => new \Railt\Compiler\Rule\Token(115, 'T_EXTEND', null, -1, false),
                116 => new \Railt\Compiler\Rule\Concatenation(116, ['ObjectDefinition',], '#ExtendDefinition'),
                117 => new \Railt\Compiler\Rule\Concatenation(117, ['InterfaceDefinition',], '#ExtendDefinition'),
                118 => new \Railt\Compiler\Rule\Concatenation(118, ['EnumDefinition',], '#ExtendDefinition'),
                119 => new \Railt\Compiler\Rule\Concatenation(119, ['UnionDefinition',], '#ExtendDefinition'),
                120 => new \Railt\Compiler\Rule\Concatenation(120, ['SchemaDefinition',], '#ExtendDefinition'),
                121 => new \Railt\Compiler\Rule\Concatenation(121, ['ScalarDefinition',], '#ExtendDefinition'),
                122 => new \Railt\Compiler\Rule\Concatenation(122, ['InputDefinition',], '#ExtendDefinition'),
                123 => new \Railt\Compiler\Rule\Concatenation(123, ['DirectiveDefinition',], '#ExtendDefinition'),
                124 => new \Railt\Compiler\Rule\Choice(124, [116,117,118,119,120,121,122,123,], null),
                'ExtendDefinition' => new \Railt\Compiler\Rule\Concatenation('ExtendDefinition', [114,115,124,], null),
                126 => new \Railt\Compiler\Rule\Repetition(126, 0, 1, 'Documentation', null),
                127 => new \Railt\Compiler\Rule\Token(127, 'T_DIRECTIVE', null, -1, false),
                128 => new \Railt\Compiler\Rule\Token(128, 'T_DIRECTIVE_AT', null, -1, false),
                129 => new \Railt\Compiler\Rule\Repetition(129, 0, -1, 'DirectiveDefinitionArguments', null),
                130 => new \Railt\Compiler\Rule\Token(130, 'T_ON', null, -1, false),
                131 => new \Railt\Compiler\Rule\Repetition(131, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinition', [126,127,128,'Name',129,130,131,], '#DirectiveDefinition'),
                133 => new \Railt\Compiler\Rule\Token(133, 'T_PARENTHESIS_OPEN', null, -1, false),
                134 => new \Railt\Compiler\Rule\Repetition(134, 0, -1, 'DirectiveDefinitionArgument', null),
                135 => new \Railt\Compiler\Rule\Token(135, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArguments', [133,134,135,], null),
                137 => new \Railt\Compiler\Rule\Repetition(137, 0, 1, 'Documentation', null),
                138 => new \Railt\Compiler\Rule\Token(138, 'T_COLON', null, -1, false),
                139 => new \Railt\Compiler\Rule\Repetition(139, 0, 1, 'DirectiveDefinitionDefaultValue', null),
                140 => new \Railt\Compiler\Rule\Repetition(140, 0, -1, 'Directive', null),
                'DirectiveDefinitionArgument' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionArgument', [137,'Key',138,'ValueDefinition',139,140,], '#Argument'),
                142 => new \Railt\Compiler\Rule\Token(142, 'T_OR', null, -1, false),
                143 => new \Railt\Compiler\Rule\Concatenation(143, [142,'Key',], null),
                144 => new \Railt\Compiler\Rule\Repetition(144, 0, -1, 143, null),
                'DirectiveDefinitionTargets' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionTargets', ['Key',144,], '#Target'),
                146 => new \Railt\Compiler\Rule\Token(146, 'T_EQUAL', null, -1, false),
                'DirectiveDefinitionDefaultValue' => new \Railt\Compiler\Rule\Concatenation('DirectiveDefinitionDefaultValue', [146,'Value',], null),
                148 => new \Railt\Compiler\Rule\Repetition(148, 0, 1, 'Documentation', null),
                149 => new \Railt\Compiler\Rule\Token(149, 'T_TYPE', null, -1, false),
                150 => new \Railt\Compiler\Rule\Repetition(150, 0, 1, 'ObjectDefinitionImplements', null),
                151 => new \Railt\Compiler\Rule\Repetition(151, 0, -1, 'Directive', null),
                152 => new \Railt\Compiler\Rule\Token(152, 'T_BRACE_OPEN', null, -1, false),
                153 => new \Railt\Compiler\Rule\Repetition(153, 0, -1, 'ObjectDefinitionField', null),
                154 => new \Railt\Compiler\Rule\Token(154, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinition', [148,149,'Name',150,151,152,153,154,], '#ObjectDefinition'),
                156 => new \Railt\Compiler\Rule\Token(156, 'T_TYPE_IMPLEMENTS', null, -1, false),
                157 => new \Railt\Compiler\Rule\Repetition(157, 0, -1, 'Key', null),
                158 => new \Railt\Compiler\Rule\Token(158, 'T_AND', null, -1, false),
                159 => new \Railt\Compiler\Rule\Concatenation(159, [158,'Key',], null),
                160 => new \Railt\Compiler\Rule\Repetition(160, 0, 1, 159, null),
                'ObjectDefinitionImplements' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionImplements', [156,157,160,], '#Implements'),
                162 => new \Railt\Compiler\Rule\Repetition(162, 0, 1, 'Documentation', null),
                163 => new \Railt\Compiler\Rule\Repetition(163, 0, 1, 'Arguments', null),
                164 => new \Railt\Compiler\Rule\Token(164, 'T_COLON', null, -1, false),
                165 => new \Railt\Compiler\Rule\Concatenation(165, ['Key',163,164,'ObjectDefinitionFieldValue',], null),
                'ObjectDefinitionField' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionField', [162,165,], '#Field'),
                167 => new \Railt\Compiler\Rule\Repetition(167, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Railt\Compiler\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition',167,], null),
                169 => new \Railt\Compiler\Rule\Repetition(169, 0, 1, 'Documentation', null),
                170 => new \Railt\Compiler\Rule\Token(170, 'T_INTERFACE', null, -1, false),
                171 => new \Railt\Compiler\Rule\Repetition(171, 0, -1, 'Directive', null),
                172 => new \Railt\Compiler\Rule\Token(172, 'T_BRACE_OPEN', null, -1, false),
                173 => new \Railt\Compiler\Rule\Repetition(173, 0, -1, 'InterfaceDefinitionBody', null),
                174 => new \Railt\Compiler\Rule\Token(174, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinition', [169,170,'Name',171,172,173,174,], '#InterfaceDefinition'),
                176 => new \Railt\Compiler\Rule\Token(176, 'T_COLON', null, -1, false),
                177 => new \Railt\Compiler\Rule\Repetition(177, 0, -1, 'Directive', null),
                178 => new \Railt\Compiler\Rule\Concatenation(178, ['InterfaceDefinitionFieldKey',176,'ValueDefinition',177,], null),
                'InterfaceDefinitionBody' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionBody', [178,], '#Field'),
                180 => new \Railt\Compiler\Rule\Repetition(180, 0, 1, 'Documentation', null),
                181 => new \Railt\Compiler\Rule\Repetition(181, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Railt\Compiler\Rule\Concatenation('InterfaceDefinitionFieldKey', [180,'Key',181,], null),
                183 => new \Railt\Compiler\Rule\Repetition(183, 0, 1, 'Documentation', null),
                184 => new \Railt\Compiler\Rule\Token(184, 'T_ENUM', null, -1, false),
                185 => new \Railt\Compiler\Rule\Repetition(185, 0, -1, 'Directive', null),
                186 => new \Railt\Compiler\Rule\Token(186, 'T_BRACE_OPEN', null, -1, false),
                187 => new \Railt\Compiler\Rule\Repetition(187, 0, -1, 'EnumField', null),
                188 => new \Railt\Compiler\Rule\Token(188, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Railt\Compiler\Rule\Concatenation('EnumDefinition', [183,184,'Name',185,186,187,188,], '#EnumDefinition'),
                190 => new \Railt\Compiler\Rule\Repetition(190, 0, 1, 'Documentation', null),
                191 => new \Railt\Compiler\Rule\Repetition(191, 0, -1, 'Directive', null),
                192 => new \Railt\Compiler\Rule\Concatenation(192, ['EnumValue',191,], null),
                'EnumField' => new \Railt\Compiler\Rule\Concatenation('EnumField', [190,192,], '#Value'),
                194 => new \Railt\Compiler\Rule\Token(194, 'T_NAME', null, -1, true),
                195 => new \Railt\Compiler\Rule\Choice(195, [194,'Keyword',], null),
                'EnumValue' => new \Railt\Compiler\Rule\Concatenation('EnumValue', [195,], '#Name'),
                197 => new \Railt\Compiler\Rule\Repetition(197, 0, 1, 'Documentation', null),
                198 => new \Railt\Compiler\Rule\Token(198, 'T_UNION', null, -1, false),
                199 => new \Railt\Compiler\Rule\Repetition(199, 0, -1, 'Directive', null),
                200 => new \Railt\Compiler\Rule\Token(200, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Railt\Compiler\Rule\Concatenation('UnionDefinition', [197,198,'Name',199,200,'UnionBody',], '#UnionDefinition'),
                202 => new \Railt\Compiler\Rule\Token(202, 'T_OR', null, -1, false),
                203 => new \Railt\Compiler\Rule\Repetition(203, 0, 1, 202, null),
                204 => new \Railt\Compiler\Rule\Repetition(204, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Railt\Compiler\Rule\Concatenation('UnionBody', [203,204,], '#Relations'),
                206 => new \Railt\Compiler\Rule\Token(206, 'T_OR', null, -1, false),
                207 => new \Railt\Compiler\Rule\Concatenation(207, [206,'Name',], null),
                208 => new \Railt\Compiler\Rule\Repetition(208, 0, -1, 207, null),
                'UnionUnitesList' => new \Railt\Compiler\Rule\Concatenation('UnionUnitesList', ['Name',208,], null),
                210 => new \Railt\Compiler\Rule\Token(210, 'T_PARENTHESIS_OPEN', null, -1, false),
                211 => new \Railt\Compiler\Rule\Repetition(211, 0, -1, 'ArgumentPair', null),
                212 => new \Railt\Compiler\Rule\Token(212, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Railt\Compiler\Rule\Concatenation('Arguments', [210,211,212,], null),
                214 => new \Railt\Compiler\Rule\Repetition(214, 0, 1, 'Documentation', null),
                215 => new \Railt\Compiler\Rule\Token(215, 'T_COLON', null, -1, false),
                216 => new \Railt\Compiler\Rule\Repetition(216, 0, 1, 'ArgumentDefaultValue', null),
                217 => new \Railt\Compiler\Rule\Repetition(217, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Railt\Compiler\Rule\Concatenation('ArgumentPair', [214,'Key',215,'ValueDefinition',216,217,], '#Argument'),
                'ArgumentValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentValue', ['ValueDefinition',], '#Type'),
                220 => new \Railt\Compiler\Rule\Token(220, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Railt\Compiler\Rule\Concatenation('ArgumentDefaultValue', [220,'Value',], null),
                222 => new \Railt\Compiler\Rule\Token(222, 'T_DIRECTIVE_AT', null, -1, false),
                223 => new \Railt\Compiler\Rule\Repetition(223, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Railt\Compiler\Rule\Concatenation('Directive', [222,'Name',223,], '#Directive'),
                225 => new \Railt\Compiler\Rule\Token(225, 'T_PARENTHESIS_OPEN', null, -1, false),
                226 => new \Railt\Compiler\Rule\Repetition(226, 0, -1, 'DirectiveArgumentPair', null),
                227 => new \Railt\Compiler\Rule\Token(227, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Railt\Compiler\Rule\Concatenation('DirectiveArguments', [225,226,227,], null),
                229 => new \Railt\Compiler\Rule\Token(229, 'T_COLON', null, -1, false),
                'DirectiveArgumentPair' => new \Railt\Compiler\Rule\Concatenation('DirectiveArgumentPair', ['Key',229,'Value',], '#Argument'),],
            [
    'lexer.unicode' => true,
    'parser.lookahead' => 1024,
    'error.unexpected_token' => 'Railt\\Compiler\\Exception\\UnexpectedTokenException',
    'error.unrecognized_token' => 'Railt\\Compiler\\Exception\\UnrecognizedTokenException',
]        );

        
        $this->getRule('Document')->setDefaultId('#Document');
        $this->getRule('Document')->setPPRepresentation(' Directive()* Definition()*');
        $this->getRule('Definition')->setPPRepresentation(' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()');
        $this->getRule('ValueKeyword')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE> | <T_NULL>');
        $this->getRule('Keyword')->setPPRepresentation(' <T_ON> | <T_TYPE> | <T_TYPE_IMPLEMENTS> | <T_ENUM> | <T_UNION> | <T_INTERFACE> | <T_SCHEMA> | <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCHEMA_SUBSCRIPTION> | <T_SCALAR> | <T_DIRECTIVE> | <T_INPUT> | <T_EXTEND>');
        $this->getRule('Number')->setPPRepresentation(' <T_NUMBER_VALUE>');
        $this->getRule('Nullable')->setPPRepresentation(' <T_NULL>');
        $this->getRule('Boolean')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE>');
        $this->getRule('String')->setPPRepresentation(' <T_MULTILINE_STRING> | <T_STRING>');
        $this->getRule('Word')->setPPRepresentation(' <T_NAME> | ValueKeyword()');
        $this->getRule('Name')->setDefaultId('#Name');
        $this->getRule('Name')->setPPRepresentation(' <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCHEMA_SUBSCRIPTION> | Word()');
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
        $this->getRule('DirectiveArgumentPair')->setPPRepresentation(' Key() ::T_COLON:: Value() #Argument');    }
}
