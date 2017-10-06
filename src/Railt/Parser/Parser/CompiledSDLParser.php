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
                78 => new \Hoa\Compiler\Llk\Rule\Choice(78, ['SchemaDefinitionMutation', 'SchemaDefinitionSubscription'], null),
                79 => new \Hoa\Compiler\Llk\Rule\Repetition(79, 0, -1, 78, null),
                80 => new \Hoa\Compiler\Llk\Rule\Choice(80, ['SchemaDefinitionMutation', 'SchemaDefinitionSubscription'], null),
                81 => new \Hoa\Compiler\Llk\Rule\Repetition(81, 0, -1, 80, null),
                'SchemaDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionBody', [79, 'SchemaDefinitionQuery', 81], null),
                83 => new \Hoa\Compiler\Llk\Rule\Repetition(83, 0, 1, 'Documentation', null),
                84 => new \Hoa\Compiler\Llk\Rule\Token(84, 'T_SCHEMA_QUERY', null, -1, false),
                85 => new \Hoa\Compiler\Llk\Rule\Token(85, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionQuery', [83, 84, 85, 'SchemaDefinitionFieldValue'], '#Query'),
                87 => new \Hoa\Compiler\Llk\Rule\Repetition(87, 0, 1, 'Documentation', null),
                88 => new \Hoa\Compiler\Llk\Rule\Token(88, 'T_SCHEMA_MUTATION', null, -1, false),
                89 => new \Hoa\Compiler\Llk\Rule\Token(89, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionMutation', [87, 88, 89, 'SchemaDefinitionFieldValue'], '#Mutation'),
                91 => new \Hoa\Compiler\Llk\Rule\Repetition(91, 0, 1, 'Documentation', null),
                92 => new \Hoa\Compiler\Llk\Rule\Token(92, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                93 => new \Hoa\Compiler\Llk\Rule\Token(93, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionSubscription', [91, 92, 93, 'SchemaDefinitionFieldValue'], '#Subscription'),
                95 => new \Hoa\Compiler\Llk\Rule\Repetition(95, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition', 95], null),
                97 => new \Hoa\Compiler\Llk\Rule\Repetition(97, 0, 1, 'Documentation', null),
                98 => new \Hoa\Compiler\Llk\Rule\Token(98, 'T_SCALAR', null, -1, false),
                99 => new \Hoa\Compiler\Llk\Rule\Repetition(99, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ScalarDefinition', [97, 98, 'Name', 99], '#ScalarDefinition'),
                101 => new \Hoa\Compiler\Llk\Rule\Repetition(101, 0, 1, 'Documentation', null),
                102 => new \Hoa\Compiler\Llk\Rule\Token(102, 'T_INPUT', null, -1, false),
                103 => new \Hoa\Compiler\Llk\Rule\Repetition(103, 0, -1, 'Directive', null),
                104 => new \Hoa\Compiler\Llk\Rule\Token(104, 'T_BRACE_OPEN', null, -1, false),
                105 => new \Hoa\Compiler\Llk\Rule\Repetition(105, 1, -1, 'InputDefinitionField', null),
                106 => new \Hoa\Compiler\Llk\Rule\Token(106, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinition', [101, 102, 'Name', 103, 104, 105, 106], '#InputDefinition'),
                108 => new \Hoa\Compiler\Llk\Rule\Repetition(108, 0, 1, 'Documentation', null),
                109 => new \Hoa\Compiler\Llk\Rule\Token(109, 'T_COLON', null, -1, false),
                110 => new \Hoa\Compiler\Llk\Rule\Repetition(110, 0, 1, 'InputDefinitionDefaultValue', null),
                111 => new \Hoa\Compiler\Llk\Rule\Repetition(111, 0, -1, 'Directive', null),
                112 => new \Hoa\Compiler\Llk\Rule\Concatenation(112, ['Key', 109, 'ValueDefinition', 110, 111], null),
                'InputDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionField', [108, 112], '#Argument'),
                114 => new \Hoa\Compiler\Llk\Rule\Token(114, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionDefaultValue', [114, 'Value'], null),
                116 => new \Hoa\Compiler\Llk\Rule\Repetition(116, 0, 1, 'Documentation', null),
                117 => new \Hoa\Compiler\Llk\Rule\Token(117, 'T_EXTEND', null, -1, false),
                118 => new \Hoa\Compiler\Llk\Rule\Concatenation(118, ['ObjectDefinition'], '#ExtendDefinition'),
                119 => new \Hoa\Compiler\Llk\Rule\Concatenation(119, ['InterfaceDefinition'], '#ExtendDefinition'),
                120 => new \Hoa\Compiler\Llk\Rule\Concatenation(120, ['EnumDefinition'], '#ExtendDefinition'),
                121 => new \Hoa\Compiler\Llk\Rule\Concatenation(121, ['UnionDefinition'], '#ExtendDefinition'),
                122 => new \Hoa\Compiler\Llk\Rule\Concatenation(122, ['SchemaDefinition'], '#ExtendDefinition'),
                123 => new \Hoa\Compiler\Llk\Rule\Concatenation(123, ['ScalarDefinition'], '#ExtendDefinition'),
                124 => new \Hoa\Compiler\Llk\Rule\Concatenation(124, ['InputDefinition'], '#ExtendDefinition'),
                125 => new \Hoa\Compiler\Llk\Rule\Concatenation(125, ['DirectiveDefinition'], '#ExtendDefinition'),
                126 => new \Hoa\Compiler\Llk\Rule\Choice(126, [118, 119, 120, 121, 122, 123, 124, 125], null),
                'ExtendDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ExtendDefinition', [116, 117, 126], null),
                128 => new \Hoa\Compiler\Llk\Rule\Repetition(128, 0, 1, 'Documentation', null),
                129 => new \Hoa\Compiler\Llk\Rule\Token(129, 'T_DIRECTIVE', null, -1, false),
                130 => new \Hoa\Compiler\Llk\Rule\Token(130, 'T_DIRECTIVE_AT', null, -1, false),
                131 => new \Hoa\Compiler\Llk\Rule\Repetition(131, 0, -1, 'DirectiveDefinitionArguments', null),
                132 => new \Hoa\Compiler\Llk\Rule\Token(132, 'T_ON', null, -1, false),
                133 => new \Hoa\Compiler\Llk\Rule\Repetition(133, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinition', [128, 129, 130, 'Name', 131, 132, 133], '#DirectiveDefinition'),
                135 => new \Hoa\Compiler\Llk\Rule\Token(135, 'T_PARENTHESIS_OPEN', null, -1, false),
                136 => new \Hoa\Compiler\Llk\Rule\Repetition(136, 0, -1, 'DirectiveDefinitionArgument', null),
                137 => new \Hoa\Compiler\Llk\Rule\Token(137, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArguments', [135, 136, 137], null),
                139 => new \Hoa\Compiler\Llk\Rule\Repetition(139, 0, 1, 'Documentation', null),
                140 => new \Hoa\Compiler\Llk\Rule\Token(140, 'T_COLON', null, -1, false),
                141 => new \Hoa\Compiler\Llk\Rule\Repetition(141, 0, 1, 'DirectiveDefinitionDefaultValue', null),
                'DirectiveDefinitionArgument' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArgument', [139, 'Key', 140, 'ValueDefinition', 141], '#Argument'),
                143 => new \Hoa\Compiler\Llk\Rule\Token(143, 'T_OR', null, -1, false),
                144 => new \Hoa\Compiler\Llk\Rule\Concatenation(144, [143, 'Key'], null),
                145 => new \Hoa\Compiler\Llk\Rule\Repetition(145, 0, -1, 144, null),
                'DirectiveDefinitionTargets' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionTargets', ['Key', 145], '#Target'),
                147 => new \Hoa\Compiler\Llk\Rule\Token(147, 'T_EQUAL', null, -1, false),
                'DirectiveDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionDefaultValue', [147, 'Value'], null),
                149 => new \Hoa\Compiler\Llk\Rule\Repetition(149, 0, 1, 'Documentation', null),
                150 => new \Hoa\Compiler\Llk\Rule\Token(150, 'T_TYPE', null, -1, false),
                151 => new \Hoa\Compiler\Llk\Rule\Repetition(151, 0, 1, 'ObjectDefinitionImplements', null),
                152 => new \Hoa\Compiler\Llk\Rule\Repetition(152, 0, -1, 'Directive', null),
                153 => new \Hoa\Compiler\Llk\Rule\Token(153, 'T_BRACE_OPEN', null, -1, false),
                154 => new \Hoa\Compiler\Llk\Rule\Repetition(154, 0, -1, 'ObjectDefinitionField', null),
                155 => new \Hoa\Compiler\Llk\Rule\Token(155, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinition', [149, 150, 'Name', 151, 152, 153, 154, 155], '#ObjectDefinition'),
                157 => new \Hoa\Compiler\Llk\Rule\Token(157, 'T_TYPE_IMPLEMENTS', null, -1, false),
                158 => new \Hoa\Compiler\Llk\Rule\Repetition(158, 1, -1, 'Key', null),
                'ObjectDefinitionImplements' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionImplements', [157, 158], '#Implements'),
                160 => new \Hoa\Compiler\Llk\Rule\Repetition(160, 0, 1, 'Documentation', null),
                161 => new \Hoa\Compiler\Llk\Rule\Repetition(161, 0, 1, 'Arguments', null),
                162 => new \Hoa\Compiler\Llk\Rule\Token(162, 'T_COLON', null, -1, false),
                163 => new \Hoa\Compiler\Llk\Rule\Concatenation(163, ['Key', 161, 162, 'ObjectDefinitionFieldValue'], null),
                'ObjectDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionField', [160, 163], '#Field'),
                165 => new \Hoa\Compiler\Llk\Rule\Repetition(165, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition', 165], null),
                167 => new \Hoa\Compiler\Llk\Rule\Repetition(167, 0, 1, 'Documentation', null),
                168 => new \Hoa\Compiler\Llk\Rule\Token(168, 'T_INTERFACE', null, -1, false),
                169 => new \Hoa\Compiler\Llk\Rule\Repetition(169, 0, -1, 'Directive', null),
                170 => new \Hoa\Compiler\Llk\Rule\Token(170, 'T_BRACE_OPEN', null, -1, false),
                171 => new \Hoa\Compiler\Llk\Rule\Repetition(171, 0, -1, 'InterfaceDefinitionBody', null),
                172 => new \Hoa\Compiler\Llk\Rule\Token(172, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinition', [167, 168, 'Name', 169, 170, 171, 172], '#InterfaceDefinition'),
                174 => new \Hoa\Compiler\Llk\Rule\Token(174, 'T_COLON', null, -1, false),
                175 => new \Hoa\Compiler\Llk\Rule\Repetition(175, 0, -1, 'Directive', null),
                176 => new \Hoa\Compiler\Llk\Rule\Concatenation(176, ['InterfaceDefinitionFieldKey', 174, 'ValueDefinition', 175], null),
                'InterfaceDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionBody', [176], '#Field'),
                178 => new \Hoa\Compiler\Llk\Rule\Repetition(178, 0, 1, 'Documentation', null),
                179 => new \Hoa\Compiler\Llk\Rule\Repetition(179, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionFieldKey', [178, 'Key', 179], null),
                181 => new \Hoa\Compiler\Llk\Rule\Repetition(181, 0, 1, 'Documentation', null),
                182 => new \Hoa\Compiler\Llk\Rule\Token(182, 'T_ENUM', null, -1, false),
                183 => new \Hoa\Compiler\Llk\Rule\Repetition(183, 0, -1, 'Directive', null),
                184 => new \Hoa\Compiler\Llk\Rule\Token(184, 'T_BRACE_OPEN', null, -1, false),
                185 => new \Hoa\Compiler\Llk\Rule\Repetition(185, 1, -1, 'EnumField', null),
                186 => new \Hoa\Compiler\Llk\Rule\Token(186, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumDefinition', [181, 182, 'Name', 183, 184, 185, 186], '#EnumDefinition'),
                188 => new \Hoa\Compiler\Llk\Rule\Repetition(188, 0, 1, 'Documentation', null),
                189 => new \Hoa\Compiler\Llk\Rule\Repetition(189, 0, -1, 'Directive', null),
                190 => new \Hoa\Compiler\Llk\Rule\Concatenation(190, ['EnumValue', 189], null),
                'EnumField' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumField', [188, 190], '#Value'),
                192 => new \Hoa\Compiler\Llk\Rule\Token(192, 'T_NAME', null, -1, true),
                193 => new \Hoa\Compiler\Llk\Rule\Choice(193, [192, 'Keyword'], null),
                'EnumValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumValue', [193], '#Name'),
                195 => new \Hoa\Compiler\Llk\Rule\Repetition(195, 0, 1, 'Documentation', null),
                196 => new \Hoa\Compiler\Llk\Rule\Token(196, 'T_UNION', null, -1, false),
                197 => new \Hoa\Compiler\Llk\Rule\Repetition(197, 0, -1, 'Directive', null),
                198 => new \Hoa\Compiler\Llk\Rule\Token(198, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionDefinition', [195, 196, 'Name', 197, 198, 'UnionBody'], '#UnionDefinition'),
                200 => new \Hoa\Compiler\Llk\Rule\Token(200, 'T_OR', null, -1, false),
                201 => new \Hoa\Compiler\Llk\Rule\Repetition(201, 0, 1, 200, null),
                202 => new \Hoa\Compiler\Llk\Rule\Repetition(202, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionBody', [201, 202], '#Relations'),
                204 => new \Hoa\Compiler\Llk\Rule\Token(204, 'T_OR', null, -1, false),
                205 => new \Hoa\Compiler\Llk\Rule\Concatenation(205, [204, 'Name'], null),
                206 => new \Hoa\Compiler\Llk\Rule\Repetition(206, 0, -1, 205, null),
                'UnionUnitesList' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionUnitesList', ['Name', 206], null),
                208 => new \Hoa\Compiler\Llk\Rule\Token(208, 'T_PARENTHESIS_OPEN', null, -1, false),
                209 => new \Hoa\Compiler\Llk\Rule\Repetition(209, 0, -1, 'ArgumentPair', null),
                210 => new \Hoa\Compiler\Llk\Rule\Token(210, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('Arguments', [208, 209, 210], null),
                212 => new \Hoa\Compiler\Llk\Rule\Repetition(212, 0, 1, 'Documentation', null),
                213 => new \Hoa\Compiler\Llk\Rule\Token(213, 'T_COLON', null, -1, false),
                214 => new \Hoa\Compiler\Llk\Rule\Repetition(214, 0, 1, 'ArgumentDefaultValue', null),
                215 => new \Hoa\Compiler\Llk\Rule\Repetition(215, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentPair', [212, 'Key', 213, 'ValueDefinition', 214, 215], '#Argument'),
                'ArgumentValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentValue', ['ValueDefinition'], '#Type'),
                218 => new \Hoa\Compiler\Llk\Rule\Token(218, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentDefaultValue', [218, 'Value'], null),
                220 => new \Hoa\Compiler\Llk\Rule\Token(220, 'T_DIRECTIVE_AT', null, -1, false),
                221 => new \Hoa\Compiler\Llk\Rule\Repetition(221, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Hoa\Compiler\Llk\Rule\Concatenation('Directive', [220, 'Name', 221], '#Directive'),
                223 => new \Hoa\Compiler\Llk\Rule\Token(223, 'T_PARENTHESIS_OPEN', null, -1, false),
                224 => new \Hoa\Compiler\Llk\Rule\Repetition(224, 0, -1, 'DirectivePair', null),
                225 => new \Hoa\Compiler\Llk\Rule\Token(225, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArguments', [223, 224, 225], null),
                227 => new \Hoa\Compiler\Llk\Rule\Token(227, 'T_COLON', null, -1, false),
                'DirectivePair' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectivePair', ['Key', 227, 'Value'], '#Argument'),
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
        $this->getRule('SchemaDefinitionBody')->setPPRepresentation(' ( SchemaDefinitionMutation() | SchemaDefinitionSubscription() )* SchemaDefinitionQuery() ( SchemaDefinitionMutation() | SchemaDefinitionSubscription() )*');
        $this->getRule('SchemaDefinitionQuery')->setPPRepresentation(' Documentation()? ::T_SCHEMA_QUERY:: ::T_COLON:: SchemaDefinitionFieldValue() #Query');
        $this->getRule('SchemaDefinitionMutation')->setPPRepresentation(' Documentation()? ::T_SCHEMA_MUTATION:: ::T_COLON:: SchemaDefinitionFieldValue() #Mutation');
        $this->getRule('SchemaDefinitionSubscription')->setPPRepresentation(' Documentation()? ::T_SCHEMA_SUBSCRIPTION:: ::T_COLON:: SchemaDefinitionFieldValue() #Subscription');
        $this->getRule('SchemaDefinitionFieldValue')->setPPRepresentation(' ValueDefinition() Directive()*');
        $this->getRule('ScalarDefinition')->setDefaultId('#ScalarDefinition');
        $this->getRule('ScalarDefinition')->setPPRepresentation(' Documentation()? ::T_SCALAR:: Name() Directive()*');
        $this->getRule('InputDefinition')->setDefaultId('#InputDefinition');
        $this->getRule('InputDefinition')->setPPRepresentation(' Documentation()? ::T_INPUT:: Name() Directive()* ::T_BRACE_OPEN:: InputDefinitionField()+ ::T_BRACE_CLOSE::');
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
        $this->getRule('EnumDefinition')->setPPRepresentation(' Documentation()? ::T_ENUM:: Name() Directive()* ::T_BRACE_OPEN:: EnumField()+ ::T_BRACE_CLOSE::');
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
