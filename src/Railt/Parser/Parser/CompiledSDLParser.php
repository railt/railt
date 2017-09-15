<?php
/** 
 * This is generated file. 
 * For update sources from grammar use Railt\Parser\Parser::compileSources() method.
 */
namespace Railt\Parser\Parser;

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
                    'T_COMMA' => ',',
                    'T_ON' => 'on\b',
                    'T_NUMBER_VALUE' => '\-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?\b',
                    'T_BOOL_TRUE' => 'true\b',
                    'T_BOOL_FALSE' => 'false\b',
                    'T_NULL' => 'null\b',
                    'T_MULTILINE_STRING_OPEN:multiline_string' => '"""',
                    'T_STRING_OPEN:string' => '"',
                    'T_SCALAR_INTEGER' => 'Int\b',
                    'T_SCALAR_ANY' => 'Any\b',
                    'T_SCALAR_FLOAT' => 'Float\b',
                    'T_SCALAR_STRING' => 'String\b',
                    'T_SCALAR_BOOLEAN' => 'Boolean\b',
                    'T_SCALAR_ID' => 'ID\b',
                    'T_TYPE' => 'type\b',
                    'T_TYPE_IMPLEMENTS' => 'implements\b',
                    'T_ENUM' => 'enum\b',
                    'T_UNION' => 'union\b',
                    'T_INTERFACE' => 'interface\b',
                    'T_SCHEMA' => 'schema\b',
                    'T_SCHEMA_QUERY' => 'query\b',
                    'T_SCHEMA_MUTATION' => 'mutation\b',
                    'T_SCALAR' => 'scalar\b',
                    'T_DIRECTIVE' => 'directive\b',
                    'T_INPUT' => 'input\b',
                    'T_EXTEND' => 'extend\b',
                    'T_NAME' => '([_A-Za-z][_0-9A-Za-z]*)',
                    'skip' => '(?:[\xfe\xff|\x20|\x09|\x0a|\x0d]+|#[^\n]*)',
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
                3 => new \Hoa\Compiler\Llk\Rule\Token(3, 'T_SCALAR_INTEGER', null, -1, true),
                4 => new \Hoa\Compiler\Llk\Rule\Token(4, 'T_SCALAR_FLOAT', null, -1, true),
                5 => new \Hoa\Compiler\Llk\Rule\Token(5, 'T_SCALAR_STRING', null, -1, true),
                6 => new \Hoa\Compiler\Llk\Rule\Token(6, 'T_SCALAR_BOOLEAN', null, -1, true),
                7 => new \Hoa\Compiler\Llk\Rule\Token(7, 'T_SCALAR_ID', null, -1, true),
                8 => new \Hoa\Compiler\Llk\Rule\Token(8, 'T_SCALAR_ANY', null, -1, true),
                'Scalar' => new \Hoa\Compiler\Llk\Rule\Choice('Scalar', [3, 4, 5, 6, 7, 8], null),
                10 => new \Hoa\Compiler\Llk\Rule\Token(10, 'T_BOOL_TRUE', null, -1, true),
                11 => new \Hoa\Compiler\Llk\Rule\Token(11, 'T_BOOL_FALSE', null, -1, true),
                12 => new \Hoa\Compiler\Llk\Rule\Token(12, 'T_NULL', null, -1, true),
                'ValueKeyword' => new \Hoa\Compiler\Llk\Rule\Choice('ValueKeyword', [10, 11, 12], null),
                14 => new \Hoa\Compiler\Llk\Rule\Token(14, 'T_ON', null, -1, true),
                15 => new \Hoa\Compiler\Llk\Rule\Token(15, 'T_TYPE', null, -1, true),
                16 => new \Hoa\Compiler\Llk\Rule\Token(16, 'T_TYPE_IMPLEMENTS', null, -1, true),
                17 => new \Hoa\Compiler\Llk\Rule\Token(17, 'T_ENUM', null, -1, true),
                18 => new \Hoa\Compiler\Llk\Rule\Token(18, 'T_UNION', null, -1, true),
                19 => new \Hoa\Compiler\Llk\Rule\Token(19, 'T_INTERFACE', null, -1, true),
                20 => new \Hoa\Compiler\Llk\Rule\Token(20, 'T_SCHEMA', null, -1, true),
                21 => new \Hoa\Compiler\Llk\Rule\Token(21, 'T_SCHEMA_QUERY', null, -1, true),
                22 => new \Hoa\Compiler\Llk\Rule\Token(22, 'T_SCHEMA_MUTATION', null, -1, true),
                23 => new \Hoa\Compiler\Llk\Rule\Token(23, 'T_SCALAR', null, -1, true),
                24 => new \Hoa\Compiler\Llk\Rule\Token(24, 'T_DIRECTIVE', null, -1, true),
                25 => new \Hoa\Compiler\Llk\Rule\Token(25, 'T_INPUT', null, -1, true),
                26 => new \Hoa\Compiler\Llk\Rule\Token(26, 'T_EXTEND', null, -1, true),
                'Keyword' => new \Hoa\Compiler\Llk\Rule\Choice('Keyword', [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26], null),
                'Number' => new \Hoa\Compiler\Llk\Rule\Token('Number', 'T_NUMBER_VALUE', null, -1, true),
                'Nullable' => new \Hoa\Compiler\Llk\Rule\Token('Nullable', 'T_NULL', null, -1, true),
                30 => new \Hoa\Compiler\Llk\Rule\Token(30, 'T_BOOL_TRUE', null, -1, true),
                31 => new \Hoa\Compiler\Llk\Rule\Token(31, 'T_BOOL_FALSE', null, -1, true),
                'Boolean' => new \Hoa\Compiler\Llk\Rule\Choice('Boolean', [30, 31], null),
                33 => new \Hoa\Compiler\Llk\Rule\Token(33, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                34 => new \Hoa\Compiler\Llk\Rule\Token(34, 'T_MULTILINE_STRING', null, -1, true),
                35 => new \Hoa\Compiler\Llk\Rule\Token(35, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                36 => new \Hoa\Compiler\Llk\Rule\Concatenation(36, [33, 34, 35], null),
                37 => new \Hoa\Compiler\Llk\Rule\Token(37, 'T_STRING_OPEN', null, -1, false),
                38 => new \Hoa\Compiler\Llk\Rule\Token(38, 'T_STRING', null, -1, true),
                39 => new \Hoa\Compiler\Llk\Rule\Token(39, 'T_STRING_CLOSE', null, -1, false),
                40 => new \Hoa\Compiler\Llk\Rule\Concatenation(40, [37, 38, 39], null),
                'String' => new \Hoa\Compiler\Llk\Rule\Choice('String', [36, 40], null),
                'Relation' => new \Hoa\Compiler\Llk\Rule\Token('Relation', 'T_NAME', null, -1, true),
                43 => new \Hoa\Compiler\Llk\Rule\Choice(43, ['Scalar', 'ValueKeyword', 'Relation'], null),
                'Name' => new \Hoa\Compiler\Llk\Rule\Concatenation('Name', [43], '#Name'),
                45 => new \Hoa\Compiler\Llk\Rule\Choice(45, ['Scalar', 'Keyword', 'ValueKeyword', 'Relation'], null),
                'Key' => new \Hoa\Compiler\Llk\Rule\Concatenation('Key', [45], '#Name'),
                47 => new \Hoa\Compiler\Llk\Rule\Choice(47, ['String', 'Number', 'Nullable', 'Keyword', 'Scalar', 'Relation', 'Object', 'List', 'ValueKeyword'], null),
                'Value' => new \Hoa\Compiler\Llk\Rule\Concatenation('Value', [47], '#Value'),
                'ValueDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver'], null),
                50 => new \Hoa\Compiler\Llk\Rule\Token(50, 'T_NON_NULL', null, -1, true),
                51 => new \Hoa\Compiler\Llk\Rule\Repetition(51, 0, 1, 50, null),
                52 => new \Hoa\Compiler\Llk\Rule\Concatenation(52, ['ValueListDefinition', 51], '#List'),
                53 => new \Hoa\Compiler\Llk\Rule\Token(53, 'T_NON_NULL', null, -1, true),
                54 => new \Hoa\Compiler\Llk\Rule\Repetition(54, 0, 1, 53, null),
                55 => new \Hoa\Compiler\Llk\Rule\Concatenation(55, ['ValueScalarDefinition', 54], '#Type'),
                'ValueDefinitionResolver' => new \Hoa\Compiler\Llk\Rule\Choice('ValueDefinitionResolver', [52, 55], null),
                57 => new \Hoa\Compiler\Llk\Rule\Token(57, 'T_BRACKET_OPEN', null, -1, false),
                58 => new \Hoa\Compiler\Llk\Rule\Token(58, 'T_NON_NULL', null, -1, true),
                59 => new \Hoa\Compiler\Llk\Rule\Repetition(59, 0, 1, 58, null),
                60 => new \Hoa\Compiler\Llk\Rule\Concatenation(60, ['ValueScalarDefinition', 59], '#Type'),
                61 => new \Hoa\Compiler\Llk\Rule\Token(61, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueListDefinition', [57, 60, 61], null),
                63 => new \Hoa\Compiler\Llk\Rule\Token(63, 'T_NAME', null, -1, true),
                'ValueScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Choice('ValueScalarDefinition', ['Keyword', 'Scalar', 63], null),
                65 => new \Hoa\Compiler\Llk\Rule\Token(65, 'T_BRACE_OPEN', null, -1, false),
                66 => new \Hoa\Compiler\Llk\Rule\Token(66, 'T_COMMA', null, -1, false),
                67 => new \Hoa\Compiler\Llk\Rule\Concatenation(67, [66, 'ObjectPair'], null),
                68 => new \Hoa\Compiler\Llk\Rule\Repetition(68, 0, -1, 67, null),
                69 => new \Hoa\Compiler\Llk\Rule\Concatenation(69, ['ObjectPair', 68], null),
                70 => new \Hoa\Compiler\Llk\Rule\Repetition(70, 0, 1, 69, null),
                71 => new \Hoa\Compiler\Llk\Rule\Token(71, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Hoa\Compiler\Llk\Rule\Concatenation('Object', [65, 70, 71], '#Object'),
                73 => new \Hoa\Compiler\Llk\Rule\Token(73, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectPair', ['Key', 73, 'Value'], '#ObjectPair'),
                75 => new \Hoa\Compiler\Llk\Rule\Token(75, 'T_BRACKET_OPEN', null, -1, false),
                76 => new \Hoa\Compiler\Llk\Rule\Token(76, 'T_COMMA', null, -1, false),
                77 => new \Hoa\Compiler\Llk\Rule\Concatenation(77, [76, 'Value'], null),
                78 => new \Hoa\Compiler\Llk\Rule\Repetition(78, 0, -1, 77, null),
                79 => new \Hoa\Compiler\Llk\Rule\Concatenation(79, ['Value', 78], null),
                80 => new \Hoa\Compiler\Llk\Rule\Repetition(80, 0, 1, 79, null),
                81 => new \Hoa\Compiler\Llk\Rule\Token(81, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Hoa\Compiler\Llk\Rule\Concatenation('List', [75, 80, 81], '#List'),
                83 => new \Hoa\Compiler\Llk\Rule\Token(83, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                84 => new \Hoa\Compiler\Llk\Rule\Token(84, 'T_MULTILINE_STRING', null, -1, true),
                85 => new \Hoa\Compiler\Llk\Rule\Token(85, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                86 => new \Hoa\Compiler\Llk\Rule\Concatenation(86, [83, 84, 85], null),
                'Documentation' => new \Hoa\Compiler\Llk\Rule\Concatenation('Documentation', [86], '#Description'),
                88 => new \Hoa\Compiler\Llk\Rule\Repetition(88, 0, 1, 'Documentation', null),
                89 => new \Hoa\Compiler\Llk\Rule\Token(89, 'T_SCHEMA', null, -1, false),
                90 => new \Hoa\Compiler\Llk\Rule\Repetition(90, 0, -1, 'Directive', null),
                91 => new \Hoa\Compiler\Llk\Rule\Token(91, 'T_BRACE_OPEN', null, -1, false),
                92 => new \Hoa\Compiler\Llk\Rule\Token(92, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinition', [88, 89, 90, 91, 'SchemaDefinitionBody', 92], '#SchemaDefinition'),
                94 => new \Hoa\Compiler\Llk\Rule\Repetition(94, 0, 1, 'SchemaDefinitionMutation', null),
                95 => new \Hoa\Compiler\Llk\Rule\Concatenation(95, ['SchemaDefinitionQuery', 94], null),
                96 => new \Hoa\Compiler\Llk\Rule\Concatenation(96, ['SchemaDefinitionMutation', 'SchemaDefinitionQuery'], null),
                'SchemaDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Choice('SchemaDefinitionBody', [95, 96], null),
                98 => new \Hoa\Compiler\Llk\Rule\Repetition(98, 0, 1, 'Documentation', null),
                99 => new \Hoa\Compiler\Llk\Rule\Token(99, 'T_SCHEMA_QUERY', null, -1, false),
                100 => new \Hoa\Compiler\Llk\Rule\Token(100, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionQuery', [98, 99, 100, 'SchemaDefinitionFieldValue'], '#Query'),
                102 => new \Hoa\Compiler\Llk\Rule\Repetition(102, 0, 1, 'Documentation', null),
                103 => new \Hoa\Compiler\Llk\Rule\Token(103, 'T_SCHEMA_MUTATION', null, -1, false),
                104 => new \Hoa\Compiler\Llk\Rule\Token(104, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionMutation', [102, 103, 104, 'SchemaDefinitionFieldValue'], '#Mutation'),
                106 => new \Hoa\Compiler\Llk\Rule\Repetition(106, 0, -1, 'Directive', null),
                107 => new \Hoa\Compiler\Llk\Rule\Token(107, 'T_COMMA', null, -1, false),
                108 => new \Hoa\Compiler\Llk\Rule\Repetition(108, 0, 1, 107, null),
                'SchemaDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition', 106, 108], null),
                110 => new \Hoa\Compiler\Llk\Rule\Repetition(110, 0, 1, 'Documentation', null),
                111 => new \Hoa\Compiler\Llk\Rule\Token(111, 'T_SCALAR', null, -1, false),
                112 => new \Hoa\Compiler\Llk\Rule\Repetition(112, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ScalarDefinition', [110, 111, 'Name', 112], '#ScalarDefinition'),
                114 => new \Hoa\Compiler\Llk\Rule\Repetition(114, 0, 1, 'Documentation', null),
                115 => new \Hoa\Compiler\Llk\Rule\Token(115, 'T_INPUT', null, -1, false),
                116 => new \Hoa\Compiler\Llk\Rule\Repetition(116, 0, -1, 'Directive', null),
                117 => new \Hoa\Compiler\Llk\Rule\Token(117, 'T_BRACE_OPEN', null, -1, false),
                118 => new \Hoa\Compiler\Llk\Rule\Repetition(118, 1, -1, 'InputDefinitionField', null),
                119 => new \Hoa\Compiler\Llk\Rule\Token(119, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinition', [114, 115, 'Name', 116, 117, 118, 119], '#InputDefinition'),
                121 => new \Hoa\Compiler\Llk\Rule\Repetition(121, 0, 1, 'Documentation', null),
                122 => new \Hoa\Compiler\Llk\Rule\Token(122, 'T_COLON', null, -1, false),
                123 => new \Hoa\Compiler\Llk\Rule\Repetition(123, 0, 1, 'InputDefinitionDefaultValue', null),
                124 => new \Hoa\Compiler\Llk\Rule\Repetition(124, 0, -1, 'Directive', null),
                125 => new \Hoa\Compiler\Llk\Rule\Token(125, 'T_COMMA', null, -1, false),
                126 => new \Hoa\Compiler\Llk\Rule\Repetition(126, 0, 1, 125, null),
                127 => new \Hoa\Compiler\Llk\Rule\Concatenation(127, ['Key', 122, 'ValueDefinition', 123, 124, 126], null),
                'InputDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionField', [121, 127], '#Field'),
                129 => new \Hoa\Compiler\Llk\Rule\Token(129, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionDefaultValue', [129, 'Value'], '#DefaultValue'),
                131 => new \Hoa\Compiler\Llk\Rule\Repetition(131, 0, 1, 'Documentation', null),
                132 => new \Hoa\Compiler\Llk\Rule\Token(132, 'T_EXTEND', null, -1, false),
                133 => new \Hoa\Compiler\Llk\Rule\Concatenation(133, ['ObjectDefinition'], '#ExtendDefinition'),
                134 => new \Hoa\Compiler\Llk\Rule\Concatenation(134, ['InterfaceDefinition'], '#ExtendDefinition'),
                135 => new \Hoa\Compiler\Llk\Rule\Concatenation(135, ['InputDefinition'], '#ExtendDefinition'),
                136 => new \Hoa\Compiler\Llk\Rule\Choice(136, [133, 134, 135], null),
                'ExtendDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ExtendDefinition', [131, 132, 136], null),
                138 => new \Hoa\Compiler\Llk\Rule\Repetition(138, 0, 1, 'Documentation', null),
                139 => new \Hoa\Compiler\Llk\Rule\Token(139, 'T_DIRECTIVE', null, -1, false),
                140 => new \Hoa\Compiler\Llk\Rule\Token(140, 'T_DIRECTIVE_AT', null, -1, false),
                141 => new \Hoa\Compiler\Llk\Rule\Repetition(141, 0, -1, 'DirectiveDefinitionArguments', null),
                142 => new \Hoa\Compiler\Llk\Rule\Token(142, 'T_ON', null, -1, false),
                143 => new \Hoa\Compiler\Llk\Rule\Repetition(143, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinition', [138, 139, 140, 'Name', 141, 142, 143], '#DirectiveDefinition'),
                145 => new \Hoa\Compiler\Llk\Rule\Token(145, 'T_PARENTHESIS_OPEN', null, -1, false),
                146 => new \Hoa\Compiler\Llk\Rule\Token(146, 'T_COMMA', null, -1, false),
                147 => new \Hoa\Compiler\Llk\Rule\Concatenation(147, [146, 'DirectiveDefinitionArgument'], null),
                148 => new \Hoa\Compiler\Llk\Rule\Repetition(148, 0, -1, 147, null),
                149 => new \Hoa\Compiler\Llk\Rule\Concatenation(149, ['DirectiveDefinitionArgument', 148], null),
                150 => new \Hoa\Compiler\Llk\Rule\Repetition(150, 0, 1, 149, null),
                151 => new \Hoa\Compiler\Llk\Rule\Token(151, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArguments', [145, 150, 151], '#Argument'),
                153 => new \Hoa\Compiler\Llk\Rule\Repetition(153, 0, 1, 'Documentation', null),
                154 => new \Hoa\Compiler\Llk\Rule\Token(154, 'T_COLON', null, -1, false),
                'DirectiveDefinitionArgument' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArgument', [153, 'Key', 154, 'ValueDefinition'], null),
                156 => new \Hoa\Compiler\Llk\Rule\Token(156, 'T_OR', null, -1, false),
                157 => new \Hoa\Compiler\Llk\Rule\Concatenation(157, [156, 'Key'], null),
                158 => new \Hoa\Compiler\Llk\Rule\Repetition(158, 0, -1, 157, null),
                'DirectiveDefinitionTargets' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionTargets', ['Key', 158], '#Target'),
                160 => new \Hoa\Compiler\Llk\Rule\Repetition(160, 0, 1, 'Documentation', null),
                161 => new \Hoa\Compiler\Llk\Rule\Token(161, 'T_TYPE', null, -1, false),
                162 => new \Hoa\Compiler\Llk\Rule\Repetition(162, 0, 1, 'ObjectDefinitionImplements', null),
                163 => new \Hoa\Compiler\Llk\Rule\Repetition(163, 0, -1, 'Directive', null),
                164 => new \Hoa\Compiler\Llk\Rule\Token(164, 'T_BRACE_OPEN', null, -1, false),
                165 => new \Hoa\Compiler\Llk\Rule\Repetition(165, 0, -1, 'ObjectDefinitionField', null),
                166 => new \Hoa\Compiler\Llk\Rule\Token(166, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinition', [160, 161, 'Name', 162, 163, 164, 165, 166], '#ObjectDefinition'),
                168 => new \Hoa\Compiler\Llk\Rule\Token(168, 'T_TYPE_IMPLEMENTS', null, -1, false),
                169 => new \Hoa\Compiler\Llk\Rule\Token(169, 'T_COMMA', null, -1, false),
                170 => new \Hoa\Compiler\Llk\Rule\Concatenation(170, [169, 'Key'], null),
                171 => new \Hoa\Compiler\Llk\Rule\Repetition(171, 0, -1, 170, null),
                'ObjectDefinitionImplements' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionImplements', [168, 'Key', 171], '#Implements'),
                173 => new \Hoa\Compiler\Llk\Rule\Repetition(173, 0, 1, 'Documentation', null),
                174 => new \Hoa\Compiler\Llk\Rule\Repetition(174, 0, 1, 'Arguments', null),
                175 => new \Hoa\Compiler\Llk\Rule\Token(175, 'T_COLON', null, -1, false),
                176 => new \Hoa\Compiler\Llk\Rule\Concatenation(176, ['Key', 174, 175, 'ObjectDefinitionFieldValue'], null),
                'ObjectDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionField', [173, 176], '#Field'),
                178 => new \Hoa\Compiler\Llk\Rule\Repetition(178, 0, -1, 'Directive', null),
                179 => new \Hoa\Compiler\Llk\Rule\Token(179, 'T_COMMA', null, -1, false),
                180 => new \Hoa\Compiler\Llk\Rule\Repetition(180, 0, 1, 179, null),
                'ObjectDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition', 178, 180], null),
                182 => new \Hoa\Compiler\Llk\Rule\Repetition(182, 0, 1, 'Documentation', null),
                183 => new \Hoa\Compiler\Llk\Rule\Token(183, 'T_INTERFACE', null, -1, false),
                184 => new \Hoa\Compiler\Llk\Rule\Repetition(184, 0, -1, 'Directive', null),
                185 => new \Hoa\Compiler\Llk\Rule\Token(185, 'T_BRACE_OPEN', null, -1, false),
                186 => new \Hoa\Compiler\Llk\Rule\Repetition(186, 0, -1, 'InterfaceDefinitionBody', null),
                187 => new \Hoa\Compiler\Llk\Rule\Token(187, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinition', [182, 183, 'Name', 184, 185, 186, 187], '#InterfaceDefinition'),
                189 => new \Hoa\Compiler\Llk\Rule\Token(189, 'T_COLON', null, -1, false),
                190 => new \Hoa\Compiler\Llk\Rule\Repetition(190, 0, -1, 'Directive', null),
                191 => new \Hoa\Compiler\Llk\Rule\Token(191, 'T_COMMA', null, -1, false),
                192 => new \Hoa\Compiler\Llk\Rule\Repetition(192, 0, 1, 191, null),
                193 => new \Hoa\Compiler\Llk\Rule\Concatenation(193, ['InterfaceDefinitionFieldKey', 189, 'ValueDefinition', 190, 192], null),
                'InterfaceDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionBody', [193], '#Field'),
                195 => new \Hoa\Compiler\Llk\Rule\Repetition(195, 0, 1, 'Documentation', null),
                196 => new \Hoa\Compiler\Llk\Rule\Repetition(196, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionFieldKey', [195, 'Key', 196], null),
                198 => new \Hoa\Compiler\Llk\Rule\Repetition(198, 0, 1, 'Documentation', null),
                199 => new \Hoa\Compiler\Llk\Rule\Token(199, 'T_ENUM', null, -1, false),
                200 => new \Hoa\Compiler\Llk\Rule\Repetition(200, 0, -1, 'Directive', null),
                201 => new \Hoa\Compiler\Llk\Rule\Token(201, 'T_BRACE_OPEN', null, -1, false),
                202 => new \Hoa\Compiler\Llk\Rule\Repetition(202, 1, -1, 'EnumField', null),
                203 => new \Hoa\Compiler\Llk\Rule\Token(203, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumDefinition', [198, 199, 'Name', 200, 201, 202, 203], '#EnumDefinition'),
                205 => new \Hoa\Compiler\Llk\Rule\Repetition(205, 0, 1, 'Documentation', null),
                206 => new \Hoa\Compiler\Llk\Rule\Repetition(206, 0, -1, 'Directive', null),
                207 => new \Hoa\Compiler\Llk\Rule\Token(207, 'T_COMMA', null, -1, false),
                208 => new \Hoa\Compiler\Llk\Rule\Repetition(208, 0, 1, 207, null),
                209 => new \Hoa\Compiler\Llk\Rule\Concatenation(209, ['EnumValue', 206, 208], null),
                'EnumField' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumField', [205, 209], '#Value'),
                211 => new \Hoa\Compiler\Llk\Rule\Choice(211, ['Scalar', 'Keyword', 'Relation'], null),
                'EnumValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumValue', [211], '#Name'),
                213 => new \Hoa\Compiler\Llk\Rule\Repetition(213, 0, 1, 'Documentation', null),
                214 => new \Hoa\Compiler\Llk\Rule\Token(214, 'T_UNION', null, -1, false),
                215 => new \Hoa\Compiler\Llk\Rule\Repetition(215, 0, -1, 'Directive', null),
                216 => new \Hoa\Compiler\Llk\Rule\Token(216, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionDefinition', [213, 214, 'Name', 215, 216, 'UnionBody'], '#UnionDefinition'),
                218 => new \Hoa\Compiler\Llk\Rule\Token(218, 'T_OR', null, -1, false),
                219 => new \Hoa\Compiler\Llk\Rule\Repetition(219, 0, 1, 218, null),
                220 => new \Hoa\Compiler\Llk\Rule\Repetition(220, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionBody', [219, 220], '#Relations'),
                222 => new \Hoa\Compiler\Llk\Rule\Token(222, 'T_OR', null, -1, false),
                223 => new \Hoa\Compiler\Llk\Rule\Concatenation(223, [222, 'Name'], null),
                224 => new \Hoa\Compiler\Llk\Rule\Repetition(224, 0, -1, 223, null),
                'UnionUnitesList' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionUnitesList', ['Name', 224], null),
                226 => new \Hoa\Compiler\Llk\Rule\Token(226, 'T_PARENTHESIS_OPEN', null, -1, false),
                227 => new \Hoa\Compiler\Llk\Rule\Token(227, 'T_COMMA', null, -1, false),
                228 => new \Hoa\Compiler\Llk\Rule\Concatenation(228, [227, 'ArgumentPair'], null),
                229 => new \Hoa\Compiler\Llk\Rule\Repetition(229, 0, -1, 228, null),
                230 => new \Hoa\Compiler\Llk\Rule\Concatenation(230, ['ArgumentPair', 229], null),
                231 => new \Hoa\Compiler\Llk\Rule\Repetition(231, 0, 1, 230, null),
                232 => new \Hoa\Compiler\Llk\Rule\Token(232, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('Arguments', [226, 231, 232], null),
                234 => new \Hoa\Compiler\Llk\Rule\Repetition(234, 0, 1, 'Documentation', null),
                235 => new \Hoa\Compiler\Llk\Rule\Token(235, 'T_COLON', null, -1, false),
                236 => new \Hoa\Compiler\Llk\Rule\Repetition(236, 0, 1, 'ArgumentDefaultValue', null),
                237 => new \Hoa\Compiler\Llk\Rule\Repetition(237, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentPair', [234, 'Key', 235, 'ValueDefinition', 236, 237], '#Argument'),
                'ArgumentValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentValue', ['ValueDefinition'], '#Type'),
                240 => new \Hoa\Compiler\Llk\Rule\Token(240, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentDefaultValue', [240, 'Value'], null),
                242 => new \Hoa\Compiler\Llk\Rule\Token(242, 'T_DIRECTIVE_AT', null, -1, false),
                243 => new \Hoa\Compiler\Llk\Rule\Repetition(243, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Hoa\Compiler\Llk\Rule\Concatenation('Directive', [242, 'Name', 243], '#Directive'),
                245 => new \Hoa\Compiler\Llk\Rule\Token(245, 'T_PARENTHESIS_OPEN', null, -1, false),
                246 => new \Hoa\Compiler\Llk\Rule\Token(246, 'T_COMMA', null, -1, false),
                247 => new \Hoa\Compiler\Llk\Rule\Concatenation(247, [246, 'DirectivePair'], null),
                248 => new \Hoa\Compiler\Llk\Rule\Repetition(248, 0, -1, 247, null),
                249 => new \Hoa\Compiler\Llk\Rule\Concatenation(249, ['DirectivePair', 248], null),
                250 => new \Hoa\Compiler\Llk\Rule\Repetition(250, 0, 1, 249, null),
                251 => new \Hoa\Compiler\Llk\Rule\Token(251, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArguments', [245, 250, 251], null),
                253 => new \Hoa\Compiler\Llk\Rule\Token(253, 'T_COLON', null, -1, false),
                254 => new \Hoa\Compiler\Llk\Rule\Repetition(254, 0, 1, 'DirectiveArgumentDefaultValue', null),
                'DirectivePair' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectivePair', ['Key', 253, 'Value', 254], '#Argument'),
                256 => new \Hoa\Compiler\Llk\Rule\Token(256, 'T_EQUAL', null, -1, false),
                'DirectiveArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArgumentDefaultValue', [256, 'Value'], null),
            ],
            [
            ]
        );

        $this->getRule('Document')->setDefaultId('#Document');
        $this->getRule('Document')->setPPRepresentation(' Definitions()*');
        $this->getRule('Definitions')->setPPRepresentation(' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()');
        $this->getRule('Scalar')->setPPRepresentation(' <T_SCALAR_INTEGER> | <T_SCALAR_FLOAT> | <T_SCALAR_STRING> | <T_SCALAR_BOOLEAN> | <T_SCALAR_ID> | <T_SCALAR_ANY>');
        $this->getRule('ValueKeyword')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE> | <T_NULL>');
        $this->getRule('Keyword')->setPPRepresentation(' <T_ON> | <T_TYPE> | <T_TYPE_IMPLEMENTS> | <T_ENUM> | <T_UNION> | <T_INTERFACE> | <T_SCHEMA> | <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCALAR> | <T_DIRECTIVE> | <T_INPUT> | <T_EXTEND>');
        $this->getRule('Number')->setPPRepresentation(' <T_NUMBER_VALUE>');
        $this->getRule('Nullable')->setPPRepresentation(' <T_NULL>');
        $this->getRule('Boolean')->setPPRepresentation(' <T_BOOL_TRUE> | <T_BOOL_FALSE>');
        $this->getRule('String')->setPPRepresentation(' (::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE::) | (::T_STRING_OPEN:: <T_STRING> ::T_STRING_CLOSE::)');
        $this->getRule('Relation')->setPPRepresentation(' <T_NAME>');
        $this->getRule('Name')->setPPRepresentation(' ( Scalar() | ValueKeyword() | Relation() ) #Name');
        $this->getRule('Key')->setPPRepresentation(' ( Scalar() | Keyword() | ValueKeyword() | Relation() ) #Name');
        $this->getRule('Value')->setPPRepresentation(' ( String() | Number() | Nullable() | Keyword() | Scalar() | Relation() | Object() | List() | ValueKeyword() ) #Value');
        $this->getRule('ValueDefinition')->setPPRepresentation(' ValueDefinitionResolver()');
        $this->getRule('ValueDefinitionResolver')->setPPRepresentation(' (ValueListDefinition() <T_NON_NULL>? #List) | (ValueScalarDefinition() <T_NON_NULL>? #Type)');
        $this->getRule('ValueListDefinition')->setPPRepresentation(' ::T_BRACKET_OPEN:: (ValueScalarDefinition() <T_NON_NULL>? #Type) ::T_BRACKET_CLOSE::');
        $this->getRule('ValueScalarDefinition')->setPPRepresentation(' Keyword() | Scalar() | <T_NAME>');
        $this->getRule('Object')->setPPRepresentation(' ::T_BRACE_OPEN:: ( ObjectPair() ( ::T_COMMA:: ObjectPair() )* )? ::T_BRACE_CLOSE:: #Object');
        $this->getRule('ObjectPair')->setPPRepresentation(' Key() ::T_COLON:: Value() #ObjectPair');
        $this->getRule('List')->setPPRepresentation(' ::T_BRACKET_OPEN:: ( Value() ( ::T_COMMA:: Value() )* )? ::T_BRACKET_CLOSE:: #List');
        $this->getRule('Documentation')->setPPRepresentation(' ( ::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE:: ) #Description');
        $this->getRule('SchemaDefinition')->setDefaultId('#SchemaDefinition');
        $this->getRule('SchemaDefinition')->setPPRepresentation(' Documentation()? ::T_SCHEMA:: Directive()* ::T_BRACE_OPEN:: SchemaDefinitionBody() ::T_BRACE_CLOSE::');
        $this->getRule('SchemaDefinitionBody')->setPPRepresentation(' ( SchemaDefinitionQuery() SchemaDefinitionMutation()? ) | ( SchemaDefinitionMutation() SchemaDefinitionQuery() )');
        $this->getRule('SchemaDefinitionQuery')->setPPRepresentation(' Documentation()? ::T_SCHEMA_QUERY:: ::T_COLON:: SchemaDefinitionFieldValue() #Query');
        $this->getRule('SchemaDefinitionMutation')->setPPRepresentation(' Documentation()? ::T_SCHEMA_MUTATION:: ::T_COLON:: SchemaDefinitionFieldValue() #Mutation');
        $this->getRule('SchemaDefinitionFieldValue')->setPPRepresentation(' ValueDefinition() Directive()* ::T_COMMA::?');
        $this->getRule('ScalarDefinition')->setDefaultId('#ScalarDefinition');
        $this->getRule('ScalarDefinition')->setPPRepresentation(' Documentation()? ::T_SCALAR:: Name() Directive()*');
        $this->getRule('InputDefinition')->setDefaultId('#InputDefinition');
        $this->getRule('InputDefinition')->setPPRepresentation(' Documentation()? ::T_INPUT:: Name() Directive()* ::T_BRACE_OPEN:: InputDefinitionField()+ ::T_BRACE_CLOSE::');
        $this->getRule('InputDefinitionField')->setPPRepresentation(' Documentation()? ( Key() ::T_COLON:: ValueDefinition() InputDefinitionDefaultValue()? Directive()* ::T_COMMA::? ) #Field');
        $this->getRule('InputDefinitionDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value() #DefaultValue');
        $this->getRule('ExtendDefinition')->setDefaultId('#ExtendDefinition');
        $this->getRule('ExtendDefinition')->setPPRepresentation(' Documentation()? ::T_EXTEND:: ( ObjectDefinition() | InterfaceDefinition() | InputDefinition() )');
        $this->getRule('DirectiveDefinition')->setDefaultId('#DirectiveDefinition');
        $this->getRule('DirectiveDefinition')->setPPRepresentation(' Documentation()? ::T_DIRECTIVE:: ::T_DIRECTIVE_AT:: Name() DirectiveDefinitionArguments()* ::T_ON:: DirectiveDefinitionTargets()+');
        $this->getRule('DirectiveDefinitionArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: ( DirectiveDefinitionArgument() ( ::T_COMMA:: DirectiveDefinitionArgument() )* )? ::T_PARENTHESIS_CLOSE:: #Argument');
        $this->getRule('DirectiveDefinitionArgument')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition()');
        $this->getRule('DirectiveDefinitionTargets')->setPPRepresentation(' Key() (::T_OR:: Key())* #Target');
        $this->getRule('ObjectDefinition')->setDefaultId('#ObjectDefinition');
        $this->getRule('ObjectDefinition')->setPPRepresentation(' Documentation()? ::T_TYPE:: Name() ObjectDefinitionImplements()? Directive()* ::T_BRACE_OPEN:: ObjectDefinitionField()* ::T_BRACE_CLOSE::');
        $this->getRule('ObjectDefinitionImplements')->setPPRepresentation(' ::T_TYPE_IMPLEMENTS:: Key() ( ::T_COMMA:: Key() )* #Implements');
        $this->getRule('ObjectDefinitionField')->setPPRepresentation(' Documentation()? ( Key() Arguments()? ::T_COLON:: ObjectDefinitionFieldValue() ) #Field');
        $this->getRule('ObjectDefinitionFieldValue')->setPPRepresentation(' ValueDefinition() Directive()* ::T_COMMA::?');
        $this->getRule('InterfaceDefinition')->setDefaultId('#InterfaceDefinition');
        $this->getRule('InterfaceDefinition')->setPPRepresentation(' Documentation()? ::T_INTERFACE:: Name() Directive()* ::T_BRACE_OPEN:: InterfaceDefinitionBody()* ::T_BRACE_CLOSE::');
        $this->getRule('InterfaceDefinitionBody')->setPPRepresentation(' ( InterfaceDefinitionFieldKey() ::T_COLON:: ValueDefinition() Directive()* ::T_COMMA::? ) #Field');
        $this->getRule('InterfaceDefinitionFieldKey')->setPPRepresentation(' Documentation()? Key() Arguments()?');
        $this->getRule('EnumDefinition')->setDefaultId('#EnumDefinition');
        $this->getRule('EnumDefinition')->setPPRepresentation(' Documentation()? ::T_ENUM:: Name() Directive()* ::T_BRACE_OPEN:: EnumField()+ ::T_BRACE_CLOSE::');
        $this->getRule('EnumField')->setPPRepresentation(' Documentation()? ( EnumValue() Directive()* ::T_COMMA::? ) #Value');
        $this->getRule('EnumValue')->setPPRepresentation(' ( Scalar() | Keyword() | Relation() ) #Name');
        $this->getRule('UnionDefinition')->setDefaultId('#UnionDefinition');
        $this->getRule('UnionDefinition')->setPPRepresentation(' Documentation()? ::T_UNION:: Name() Directive()* ::T_EQUAL:: UnionBody()');
        $this->getRule('UnionBody')->setPPRepresentation(' ::T_OR::? UnionUnitesList()+ #Relations');
        $this->getRule('UnionUnitesList')->setPPRepresentation(' Name() (::T_OR:: Name())*');
        $this->getRule('Arguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: ( ArgumentPair() ( ::T_COMMA:: ArgumentPair() )* )? ::T_PARENTHESIS_CLOSE::');
        $this->getRule('ArgumentPair')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition() ArgumentDefaultValue()? Directive()* #Argument');
        $this->getRule('ArgumentValue')->setPPRepresentation(' ValueDefinition() #Type');
        $this->getRule('ArgumentDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
        $this->getRule('Directive')->setDefaultId('#Directive');
        $this->getRule('Directive')->setPPRepresentation(' ::T_DIRECTIVE_AT:: Name() DirectiveArguments()?');
        $this->getRule('DirectiveArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: ( DirectivePair() ( ::T_COMMA:: DirectivePair() )* )? ::T_PARENTHESIS_CLOSE::');
        $this->getRule('DirectivePair')->setPPRepresentation(' Key() ::T_COLON:: Value() DirectiveArgumentDefaultValue()? #Argument');
        $this->getRule('DirectiveArgumentDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
    }
}
