<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * WARNING: This is generated file.
 * For update sources from grammar use Railt\Compiler\Parser::compileSources() method.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser;

class CompiledSDLParser extends \Hoa\Compiler\Llk\Parser
{
    public function __construct()
    {
        parent::__construct(
            [
                'default' => [
                    'T_NON_NULL' => '!',
                    'T_VAR' => '\$',
                    'T_PARENTHESIS_OPEN' => '\(',
                    'T_PARENTHESIS_CLOSE' => '\)',
                    'T_THREE_DOTS' => '\.\.\.',
                    'T_COLON' => ':',
                    'T_EQUAL' => '=',
                    'T_DIRECTIVE_AT' => '@',
                    'T_BRACKET_OPEN' => '\[',
                    'T_BRACKET_CLOSE' => '\]',
                    'T_BRACE_OPEN' => '{',
                    'T_BRACE_CLOSE:default' => '}',
                    'T_OR' => '\|',
                    'T_ON' => 'on\b',
                    'T_NUMBER_VALUE' => '\-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?\b',
                    'T_BOOL_TRUE' => 'true\b',
                    'T_BOOL_FALSE' => 'false\b',
                    'T_NULL' => 'null\b',
                    'T_MULTILINE_STRING_OPEN:multiline_string' => '"""',
                    'T_STRING_OPEN:string' => '"',
                    'T_TYPE' => 'type\b',
                    'T_TYPE_IMPLEMENTS' => 'implements\b',
                    'T_ENUM' => 'enum\b',
                    'T_UNION' => 'union\b',
                    'T_INTERFACE' => 'interface\b',
                    'T_SCHEMA' => 'schema\b',
                    'T_SCHEMA_QUERY' => 'query\b',
                    'T_SCHEMA_MUTATION' => 'mutation\b',
                    'T_SCHEMA_SUBSCRIPTION' => 'subscription\b',
                    'T_SCALAR' => 'scalar\b',
                    'T_DIRECTIVE' => 'directive\b',
                    'T_INPUT' => 'input\b',
                    'T_EXTEND' => 'extend\b',
                    'T_NAME' => '([_A-Za-z][_0-9A-Za-z]*)',
                    'skip' => '(?:(?:[\xfe\xff|\x20|\x09|\x0a|\x0d]+|#[^\n]*)|,)',
                ],
                'multiline_string' => [
                    'T_MULTILINE_STRING' => '(?:\\\"""|(?!""").|\s)+',
                    'T_MULTILINE_STRING_CLOSE:default' => '"""',
                ],
                'string' => [
                    'T_STRING' => '[^"\\\]+(\\\.[^"\\\]*)*',
                    'T_STRING_CLOSE:default' => '"',
                ],
            ],
            [
                0 => new \Hoa\Compiler\Llk\Rule\Repetition(0, 0, -1, 'Definitions', null),
                'Document' => new \Hoa\Compiler\Llk\Rule\Concatenation('Document', [0], '#Document'),
                'Definitions' => new \Hoa\Compiler\Llk\Rule\Choice('Definitions', ['ObjectDefinition', 'InterfaceDefinition', 'EnumDefinition', 'UnionDefinition', 'SchemaDefinition', 'ScalarDefinition', 'InputDefinition', 'ExtendDefinition', 'DirectiveDefinition'], null),
                3 => new \Hoa\Compiler\Llk\Rule\Token(3, 'T_BOOL_TRUE', null, -1, true),
                4 => new \Hoa\Compiler\Llk\Rule\Token(4, 'T_BOOL_FALSE', null, -1, true),
                5 => new \Hoa\Compiler\Llk\Rule\Token(5, 'T_NULL', null, -1, true),
                'ValueKeyword' => new \Hoa\Compiler\Llk\Rule\Choice('ValueKeyword', [3, 4, 5], null),
                7 => new \Hoa\Compiler\Llk\Rule\Token(7, 'T_ON', null, -1, true),
                8 => new \Hoa\Compiler\Llk\Rule\Token(8, 'T_TYPE', null, -1, true),
                9 => new \Hoa\Compiler\Llk\Rule\Token(9, 'T_TYPE_IMPLEMENTS', null, -1, true),
                10 => new \Hoa\Compiler\Llk\Rule\Token(10, 'T_ENUM', null, -1, true),
                11 => new \Hoa\Compiler\Llk\Rule\Token(11, 'T_UNION', null, -1, true),
                12 => new \Hoa\Compiler\Llk\Rule\Token(12, 'T_INTERFACE', null, -1, true),
                13 => new \Hoa\Compiler\Llk\Rule\Token(13, 'T_SCHEMA', null, -1, true),
                14 => new \Hoa\Compiler\Llk\Rule\Token(14, 'T_SCHEMA_QUERY', null, -1, true),
                15 => new \Hoa\Compiler\Llk\Rule\Token(15, 'T_SCHEMA_MUTATION', null, -1, true),
                16 => new \Hoa\Compiler\Llk\Rule\Token(16, 'T_SCALAR', null, -1, true),
                17 => new \Hoa\Compiler\Llk\Rule\Token(17, 'T_DIRECTIVE', null, -1, true),
                18 => new \Hoa\Compiler\Llk\Rule\Token(18, 'T_INPUT', null, -1, true),
                19 => new \Hoa\Compiler\Llk\Rule\Token(19, 'T_EXTEND', null, -1, true),
                'Keyword' => new \Hoa\Compiler\Llk\Rule\Choice('Keyword', [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19], null),
                'Number' => new \Hoa\Compiler\Llk\Rule\Token('Number', 'T_NUMBER_VALUE', null, -1, true),
                'Nullable' => new \Hoa\Compiler\Llk\Rule\Token('Nullable', 'T_NULL', null, -1, true),
                23 => new \Hoa\Compiler\Llk\Rule\Token(23, 'T_BOOL_TRUE', null, -1, true),
                24 => new \Hoa\Compiler\Llk\Rule\Token(24, 'T_BOOL_FALSE', null, -1, true),
                'Boolean' => new \Hoa\Compiler\Llk\Rule\Choice('Boolean', [23, 24], null),
                26 => new \Hoa\Compiler\Llk\Rule\Token(26, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                27 => new \Hoa\Compiler\Llk\Rule\Token(27, 'T_MULTILINE_STRING', null, -1, true),
                28 => new \Hoa\Compiler\Llk\Rule\Token(28, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                29 => new \Hoa\Compiler\Llk\Rule\Concatenation(29, [26, 27, 28], null),
                30 => new \Hoa\Compiler\Llk\Rule\Token(30, 'T_STRING_OPEN', null, -1, false),
                31 => new \Hoa\Compiler\Llk\Rule\Token(31, 'T_STRING', null, -1, true),
                32 => new \Hoa\Compiler\Llk\Rule\Token(32, 'T_STRING_CLOSE', null, -1, false),
                33 => new \Hoa\Compiler\Llk\Rule\Concatenation(33, [30, 31, 32], null),
                'String' => new \Hoa\Compiler\Llk\Rule\Choice('String', [29, 33], null),
                35 => new \Hoa\Compiler\Llk\Rule\Token(35, 'T_NAME', null, -1, true),
                'Word' => new \Hoa\Compiler\Llk\Rule\Choice('Word', [35, 'ValueKeyword'], null),
                'Name' => new \Hoa\Compiler\Llk\Rule\Concatenation('Name', ['Word'], '#Name'),
                38 => new \Hoa\Compiler\Llk\Rule\Choice(38, ['String', 'Word', 'Keyword'], null),
                'Key' => new \Hoa\Compiler\Llk\Rule\Concatenation('Key', [38], '#Name'),
                40 => new \Hoa\Compiler\Llk\Rule\Choice(40, ['String', 'Number', 'Nullable', 'Keyword', 'Object', 'List', 'Word'], null),
                'Value' => new \Hoa\Compiler\Llk\Rule\Concatenation('Value', [40], '#Value'),
                'ValueDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver'], null),
                43 => new \Hoa\Compiler\Llk\Rule\Token(43, 'T_NON_NULL', null, -1, true),
                44 => new \Hoa\Compiler\Llk\Rule\Repetition(44, 0, 1, 43, null),
                45 => new \Hoa\Compiler\Llk\Rule\Concatenation(45, ['ValueListDefinition', 44], '#List'),
                46 => new \Hoa\Compiler\Llk\Rule\Token(46, 'T_NON_NULL', null, -1, true),
                47 => new \Hoa\Compiler\Llk\Rule\Repetition(47, 0, 1, 46, null),
                48 => new \Hoa\Compiler\Llk\Rule\Concatenation(48, ['ValueScalarDefinition', 47], '#Type'),
                'ValueDefinitionResolver' => new \Hoa\Compiler\Llk\Rule\Choice('ValueDefinitionResolver', [45, 48], null),
                50 => new \Hoa\Compiler\Llk\Rule\Token(50, 'T_BRACKET_OPEN', null, -1, false),
                51 => new \Hoa\Compiler\Llk\Rule\Token(51, 'T_NON_NULL', null, -1, true),
                52 => new \Hoa\Compiler\Llk\Rule\Repetition(52, 0, 1, 51, null),
                53 => new \Hoa\Compiler\Llk\Rule\Concatenation(53, ['ValueScalarDefinition', 52], '#Type'),
                54 => new \Hoa\Compiler\Llk\Rule\Token(54, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueListDefinition', [50, 53, 54], null),
                'ValueScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Choice('ValueScalarDefinition', ['Keyword', 'Word'], null),
                57 => new \Hoa\Compiler\Llk\Rule\Token(57, 'T_BRACE_OPEN', null, -1, false),
                58 => new \Hoa\Compiler\Llk\Rule\Repetition(58, 0, -1, 'ObjectPair', null),
                59 => new \Hoa\Compiler\Llk\Rule\Token(59, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Hoa\Compiler\Llk\Rule\Concatenation('Object', [57, 58, 59], '#Object'),
                61 => new \Hoa\Compiler\Llk\Rule\Token(61, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectPair', ['Key', 61, 'Value'], '#ObjectPair'),
                63 => new \Hoa\Compiler\Llk\Rule\Token(63, 'T_BRACKET_OPEN', null, -1, false),
                64 => new \Hoa\Compiler\Llk\Rule\Repetition(64, 0, -1, 'Value', null),
                65 => new \Hoa\Compiler\Llk\Rule\Token(65, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Hoa\Compiler\Llk\Rule\Concatenation('List', [63, 64, 65], '#List'),
                67 => new \Hoa\Compiler\Llk\Rule\Token(67, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                68 => new \Hoa\Compiler\Llk\Rule\Token(68, 'T_MULTILINE_STRING', null, -1, true),
                69 => new \Hoa\Compiler\Llk\Rule\Token(69, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                70 => new \Hoa\Compiler\Llk\Rule\Concatenation(70, [67, 68, 69], null),
                'Documentation' => new \Hoa\Compiler\Llk\Rule\Concatenation('Documentation', [70], '#Description'),
                72 => new \Hoa\Compiler\Llk\Rule\Repetition(72, 0, 1, 'Documentation', null),
                73 => new \Hoa\Compiler\Llk\Rule\Token(73, 'T_SCHEMA', null, -1, false),
                74 => new \Hoa\Compiler\Llk\Rule\Repetition(74, 0, -1, 'Directive', null),
                75 => new \Hoa\Compiler\Llk\Rule\Token(75, 'T_BRACE_OPEN', null, -1, false),
                76 => new \Hoa\Compiler\Llk\Rule\Token(76, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinition', [72, 73, 74, 75, 'SchemaDefinitionBody', 76], '#SchemaDefinition'),
                78 => new \Hoa\Compiler\Llk\Rule\Choice(78, ['SchemaDefinitionQuery', 'SchemaDefinitionMutation', 'SchemaDefinitionSubscription'], null),
                'SchemaDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Repetition('SchemaDefinitionBody', 0, -1, 78, null),
                80 => new \Hoa\Compiler\Llk\Rule\Repetition(80, 0, 1, 'Documentation', null),
                81 => new \Hoa\Compiler\Llk\Rule\Token(81, 'T_SCHEMA_QUERY', null, -1, false),
                82 => new \Hoa\Compiler\Llk\Rule\Token(82, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionQuery', [80, 81, 82, 'SchemaDefinitionFieldValue'], '#Query'),
                84 => new \Hoa\Compiler\Llk\Rule\Repetition(84, 0, 1, 'Documentation', null),
                85 => new \Hoa\Compiler\Llk\Rule\Token(85, 'T_SCHEMA_MUTATION', null, -1, false),
                86 => new \Hoa\Compiler\Llk\Rule\Token(86, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionMutation', [84, 85, 86, 'SchemaDefinitionFieldValue'], '#Mutation'),
                88 => new \Hoa\Compiler\Llk\Rule\Repetition(88, 0, 1, 'Documentation', null),
                89 => new \Hoa\Compiler\Llk\Rule\Token(89, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                90 => new \Hoa\Compiler\Llk\Rule\Token(90, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionSubscription', [88, 89, 90, 'SchemaDefinitionFieldValue'], '#Subscription'),
                92 => new \Hoa\Compiler\Llk\Rule\Repetition(92, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition', 92], null),
                94 => new \Hoa\Compiler\Llk\Rule\Repetition(94, 0, 1, 'Documentation', null),
                95 => new \Hoa\Compiler\Llk\Rule\Token(95, 'T_SCALAR', null, -1, false),
                96 => new \Hoa\Compiler\Llk\Rule\Repetition(96, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ScalarDefinition', [94, 95, 'Name', 96], '#ScalarDefinition'),
                98 => new \Hoa\Compiler\Llk\Rule\Repetition(98, 0, 1, 'Documentation', null),
                99 => new \Hoa\Compiler\Llk\Rule\Token(99, 'T_INPUT', null, -1, false),
                100 => new \Hoa\Compiler\Llk\Rule\Repetition(100, 0, -1, 'Directive', null),
                101 => new \Hoa\Compiler\Llk\Rule\Token(101, 'T_BRACE_OPEN', null, -1, false),
                102 => new \Hoa\Compiler\Llk\Rule\Repetition(102, 0, -1, 'InputDefinitionField', null),
                103 => new \Hoa\Compiler\Llk\Rule\Token(103, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinition', [98, 99, 'Name', 100, 101, 102, 103], '#InputDefinition'),
                105 => new \Hoa\Compiler\Llk\Rule\Repetition(105, 0, 1, 'Documentation', null),
                106 => new \Hoa\Compiler\Llk\Rule\Token(106, 'T_COLON', null, -1, false),
                107 => new \Hoa\Compiler\Llk\Rule\Repetition(107, 0, 1, 'InputDefinitionDefaultValue', null),
                108 => new \Hoa\Compiler\Llk\Rule\Repetition(108, 0, -1, 'Directive', null),
                109 => new \Hoa\Compiler\Llk\Rule\Concatenation(109, ['Key', 106, 'ValueDefinition', 107, 108], null),
                'InputDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionField', [105, 109], '#Argument'),
                111 => new \Hoa\Compiler\Llk\Rule\Token(111, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionDefaultValue', [111, 'Value'], null),
                113 => new \Hoa\Compiler\Llk\Rule\Repetition(113, 0, 1, 'Documentation', null),
                114 => new \Hoa\Compiler\Llk\Rule\Token(114, 'T_EXTEND', null, -1, false),
                115 => new \Hoa\Compiler\Llk\Rule\Concatenation(115, ['ObjectDefinition'], '#ExtendDefinition'),
                116 => new \Hoa\Compiler\Llk\Rule\Concatenation(116, ['InterfaceDefinition'], '#ExtendDefinition'),
                117 => new \Hoa\Compiler\Llk\Rule\Concatenation(117, ['EnumDefinition'], '#ExtendDefinition'),
                118 => new \Hoa\Compiler\Llk\Rule\Concatenation(118, ['UnionDefinition'], '#ExtendDefinition'),
                119 => new \Hoa\Compiler\Llk\Rule\Concatenation(119, ['SchemaDefinition'], '#ExtendDefinition'),
                120 => new \Hoa\Compiler\Llk\Rule\Concatenation(120, ['ScalarDefinition'], '#ExtendDefinition'),
                121 => new \Hoa\Compiler\Llk\Rule\Concatenation(121, ['InputDefinition'], '#ExtendDefinition'),
                122 => new \Hoa\Compiler\Llk\Rule\Concatenation(122, ['DirectiveDefinition'], '#ExtendDefinition'),
                123 => new \Hoa\Compiler\Llk\Rule\Choice(123, [115, 116, 117, 118, 119, 120, 121, 122], null),
                'ExtendDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ExtendDefinition', [113, 114, 123], null),
                125 => new \Hoa\Compiler\Llk\Rule\Repetition(125, 0, 1, 'Documentation', null),
                126 => new \Hoa\Compiler\Llk\Rule\Token(126, 'T_DIRECTIVE', null, -1, false),
                127 => new \Hoa\Compiler\Llk\Rule\Token(127, 'T_DIRECTIVE_AT', null, -1, false),
                128 => new \Hoa\Compiler\Llk\Rule\Repetition(128, 0, -1, 'DirectiveDefinitionArguments', null),
                129 => new \Hoa\Compiler\Llk\Rule\Token(129, 'T_ON', null, -1, false),
                130 => new \Hoa\Compiler\Llk\Rule\Repetition(130, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinition', [125, 126, 127, 'Name', 128, 129, 130], '#DirectiveDefinition'),
                132 => new \Hoa\Compiler\Llk\Rule\Token(132, 'T_PARENTHESIS_OPEN', null, -1, false),
                133 => new \Hoa\Compiler\Llk\Rule\Repetition(133, 0, -1, 'DirectiveDefinitionArgument', null),
                134 => new \Hoa\Compiler\Llk\Rule\Token(134, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArguments', [132, 133, 134], null),
                136 => new \Hoa\Compiler\Llk\Rule\Repetition(136, 0, 1, 'Documentation', null),
                137 => new \Hoa\Compiler\Llk\Rule\Token(137, 'T_COLON', null, -1, false),
                138 => new \Hoa\Compiler\Llk\Rule\Repetition(138, 0, 1, 'DirectiveDefinitionDefaultValue', null),
                'DirectiveDefinitionArgument' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArgument', [136, 'Key', 137, 'ValueDefinition', 138], '#Argument'),
                140 => new \Hoa\Compiler\Llk\Rule\Token(140, 'T_OR', null, -1, false),
                141 => new \Hoa\Compiler\Llk\Rule\Concatenation(141, [140, 'Key'], null),
                142 => new \Hoa\Compiler\Llk\Rule\Repetition(142, 0, -1, 141, null),
                'DirectiveDefinitionTargets' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionTargets', ['Key', 142], '#Target'),
                144 => new \Hoa\Compiler\Llk\Rule\Token(144, 'T_EQUAL', null, -1, false),
                'DirectiveDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionDefaultValue', [144, 'Value'], null),
                146 => new \Hoa\Compiler\Llk\Rule\Repetition(146, 0, 1, 'Documentation', null),
                147 => new \Hoa\Compiler\Llk\Rule\Token(147, 'T_TYPE', null, -1, false),
                148 => new \Hoa\Compiler\Llk\Rule\Repetition(148, 0, 1, 'ObjectDefinitionImplements', null),
                149 => new \Hoa\Compiler\Llk\Rule\Repetition(149, 0, -1, 'Directive', null),
                150 => new \Hoa\Compiler\Llk\Rule\Token(150, 'T_BRACE_OPEN', null, -1, false),
                151 => new \Hoa\Compiler\Llk\Rule\Repetition(151, 0, -1, 'ObjectDefinitionField', null),
                152 => new \Hoa\Compiler\Llk\Rule\Token(152, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinition', [146, 147, 'Name', 148, 149, 150, 151, 152], '#ObjectDefinition'),
                154 => new \Hoa\Compiler\Llk\Rule\Token(154, 'T_TYPE_IMPLEMENTS', null, -1, false),
                155 => new \Hoa\Compiler\Llk\Rule\Repetition(155, 1, -1, 'Key', null),
                'ObjectDefinitionImplements' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionImplements', [154, 155], '#Implements'),
                157 => new \Hoa\Compiler\Llk\Rule\Repetition(157, 0, 1, 'Documentation', null),
                158 => new \Hoa\Compiler\Llk\Rule\Repetition(158, 0, 1, 'Arguments', null),
                159 => new \Hoa\Compiler\Llk\Rule\Token(159, 'T_COLON', null, -1, false),
                160 => new \Hoa\Compiler\Llk\Rule\Concatenation(160, ['Key', 158, 159, 'ObjectDefinitionFieldValue'], null),
                'ObjectDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionField', [157, 160], '#Field'),
                162 => new \Hoa\Compiler\Llk\Rule\Repetition(162, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition', 162], null),
                164 => new \Hoa\Compiler\Llk\Rule\Repetition(164, 0, 1, 'Documentation', null),
                165 => new \Hoa\Compiler\Llk\Rule\Token(165, 'T_INTERFACE', null, -1, false),
                166 => new \Hoa\Compiler\Llk\Rule\Repetition(166, 0, -1, 'Directive', null),
                167 => new \Hoa\Compiler\Llk\Rule\Token(167, 'T_BRACE_OPEN', null, -1, false),
                168 => new \Hoa\Compiler\Llk\Rule\Repetition(168, 0, -1, 'InterfaceDefinitionBody', null),
                169 => new \Hoa\Compiler\Llk\Rule\Token(169, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinition', [164, 165, 'Name', 166, 167, 168, 169], '#InterfaceDefinition'),
                171 => new \Hoa\Compiler\Llk\Rule\Token(171, 'T_COLON', null, -1, false),
                172 => new \Hoa\Compiler\Llk\Rule\Repetition(172, 0, -1, 'Directive', null),
                173 => new \Hoa\Compiler\Llk\Rule\Concatenation(173, ['InterfaceDefinitionFieldKey', 171, 'ValueDefinition', 172], null),
                'InterfaceDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionBody', [173], '#Field'),
                175 => new \Hoa\Compiler\Llk\Rule\Repetition(175, 0, 1, 'Documentation', null),
                176 => new \Hoa\Compiler\Llk\Rule\Repetition(176, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionFieldKey', [175, 'Key', 176], null),
                178 => new \Hoa\Compiler\Llk\Rule\Repetition(178, 0, 1, 'Documentation', null),
                179 => new \Hoa\Compiler\Llk\Rule\Token(179, 'T_ENUM', null, -1, false),
                180 => new \Hoa\Compiler\Llk\Rule\Repetition(180, 0, -1, 'Directive', null),
                181 => new \Hoa\Compiler\Llk\Rule\Token(181, 'T_BRACE_OPEN', null, -1, false),
                182 => new \Hoa\Compiler\Llk\Rule\Repetition(182, 0, -1, 'EnumField', null),
                183 => new \Hoa\Compiler\Llk\Rule\Token(183, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumDefinition', [178, 179, 'Name', 180, 181, 182, 183], '#EnumDefinition'),
                185 => new \Hoa\Compiler\Llk\Rule\Repetition(185, 0, 1, 'Documentation', null),
                186 => new \Hoa\Compiler\Llk\Rule\Repetition(186, 0, -1, 'Directive', null),
                187 => new \Hoa\Compiler\Llk\Rule\Concatenation(187, ['EnumValue', 186], null),
                'EnumField' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumField', [185, 187], '#Value'),
                189 => new \Hoa\Compiler\Llk\Rule\Token(189, 'T_NAME', null, -1, true),
                190 => new \Hoa\Compiler\Llk\Rule\Choice(190, [189, 'Keyword'], null),
                'EnumValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumValue', [190], '#Name'),
                192 => new \Hoa\Compiler\Llk\Rule\Repetition(192, 0, 1, 'Documentation', null),
                193 => new \Hoa\Compiler\Llk\Rule\Token(193, 'T_UNION', null, -1, false),
                194 => new \Hoa\Compiler\Llk\Rule\Repetition(194, 0, -1, 'Directive', null),
                195 => new \Hoa\Compiler\Llk\Rule\Token(195, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionDefinition', [192, 193, 'Name', 194, 195, 'UnionBody'], '#UnionDefinition'),
                197 => new \Hoa\Compiler\Llk\Rule\Token(197, 'T_OR', null, -1, false),
                198 => new \Hoa\Compiler\Llk\Rule\Repetition(198, 0, 1, 197, null),
                199 => new \Hoa\Compiler\Llk\Rule\Repetition(199, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionBody', [198, 199], '#Relations'),
                201 => new \Hoa\Compiler\Llk\Rule\Token(201, 'T_OR', null, -1, false),
                202 => new \Hoa\Compiler\Llk\Rule\Concatenation(202, [201, 'Name'], null),
                203 => new \Hoa\Compiler\Llk\Rule\Repetition(203, 0, -1, 202, null),
                'UnionUnitesList' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionUnitesList', ['Name', 203], null),
                205 => new \Hoa\Compiler\Llk\Rule\Token(205, 'T_PARENTHESIS_OPEN', null, -1, false),
                206 => new \Hoa\Compiler\Llk\Rule\Repetition(206, 0, -1, 'ArgumentPair', null),
                207 => new \Hoa\Compiler\Llk\Rule\Token(207, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('Arguments', [205, 206, 207], null),
                209 => new \Hoa\Compiler\Llk\Rule\Repetition(209, 0, 1, 'Documentation', null),
                210 => new \Hoa\Compiler\Llk\Rule\Token(210, 'T_COLON', null, -1, false),
                211 => new \Hoa\Compiler\Llk\Rule\Repetition(211, 0, 1, 'ArgumentDefaultValue', null),
                212 => new \Hoa\Compiler\Llk\Rule\Repetition(212, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentPair', [209, 'Key', 210, 'ValueDefinition', 211, 212], '#Argument'),
                'ArgumentValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentValue', ['ValueDefinition'], '#Type'),
                215 => new \Hoa\Compiler\Llk\Rule\Token(215, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentDefaultValue', [215, 'Value'], null),
                217 => new \Hoa\Compiler\Llk\Rule\Token(217, 'T_DIRECTIVE_AT', null, -1, false),
                218 => new \Hoa\Compiler\Llk\Rule\Repetition(218, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Hoa\Compiler\Llk\Rule\Concatenation('Directive', [217, 'Name', 218], '#Directive'),
                220 => new \Hoa\Compiler\Llk\Rule\Token(220, 'T_PARENTHESIS_OPEN', null, -1, false),
                221 => new \Hoa\Compiler\Llk\Rule\Repetition(221, 0, -1, 'DirectivePair', null),
                222 => new \Hoa\Compiler\Llk\Rule\Token(222, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArguments', [220, 221, 222], null),
                224 => new \Hoa\Compiler\Llk\Rule\Token(224, 'T_COLON', null, -1, false),
                'DirectivePair' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectivePair', ['Key', 224, 'Value'], '#Argument'),
            ],
            [
            ]
        );

        $this->getRule('Document')->setDefaultId('#Document');
        $this->getRule('Document')->setPPRepresentation(' Definitions()*');
        $this->getRule('Definitions')->setPPRepresentation(' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()');
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
        $this->getRule('SchemaDefinition')->setPPRepresentation(' Documentation()? ::T_SCHEMA:: Directive()* ::T_BRACE_OPEN:: SchemaDefinitionBody() ::T_BRACE_CLOSE::');
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
        $this->getRule('DirectiveDefinitionArgument')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition() DirectiveDefinitionDefaultValue()? #Argument');
        $this->getRule('DirectiveDefinitionTargets')->setPPRepresentation(' Key() (::T_OR:: Key())* #Target');
        $this->getRule('DirectiveDefinitionDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
        $this->getRule('ObjectDefinition')->setDefaultId('#ObjectDefinition');
        $this->getRule('ObjectDefinition')->setPPRepresentation(' Documentation()? ::T_TYPE:: Name() ObjectDefinitionImplements()? Directive()* ::T_BRACE_OPEN:: ObjectDefinitionField()* ::T_BRACE_CLOSE::');
        $this->getRule('ObjectDefinitionImplements')->setPPRepresentation(' ::T_TYPE_IMPLEMENTS:: Key()+ #Implements');
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
        $this->getRule('DirectiveArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: DirectivePair()* ::T_PARENTHESIS_CLOSE::');
        $this->getRule('DirectivePair')->setPPRepresentation(' Key() ::T_COLON:: Value() #Argument');
    }
}
