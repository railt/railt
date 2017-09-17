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
                'Scalar' => new \Hoa\Compiler\Llk\Rule\Token('Scalar', 'T_NAME', null, -1, true),
                4 => new \Hoa\Compiler\Llk\Rule\Token(4, 'T_BOOL_TRUE', null, -1, true),
                5 => new \Hoa\Compiler\Llk\Rule\Token(5, 'T_BOOL_FALSE', null, -1, true),
                6 => new \Hoa\Compiler\Llk\Rule\Token(6, 'T_NULL', null, -1, true),
                'ValueKeyword' => new \Hoa\Compiler\Llk\Rule\Choice('ValueKeyword', [4, 5, 6], null),
                8 => new \Hoa\Compiler\Llk\Rule\Token(8, 'T_ON', null, -1, true),
                9 => new \Hoa\Compiler\Llk\Rule\Token(9, 'T_TYPE', null, -1, true),
                10 => new \Hoa\Compiler\Llk\Rule\Token(10, 'T_TYPE_IMPLEMENTS', null, -1, true),
                11 => new \Hoa\Compiler\Llk\Rule\Token(11, 'T_ENUM', null, -1, true),
                12 => new \Hoa\Compiler\Llk\Rule\Token(12, 'T_UNION', null, -1, true),
                13 => new \Hoa\Compiler\Llk\Rule\Token(13, 'T_INTERFACE', null, -1, true),
                14 => new \Hoa\Compiler\Llk\Rule\Token(14, 'T_SCHEMA', null, -1, true),
                15 => new \Hoa\Compiler\Llk\Rule\Token(15, 'T_SCHEMA_QUERY', null, -1, true),
                16 => new \Hoa\Compiler\Llk\Rule\Token(16, 'T_SCHEMA_MUTATION', null, -1, true),
                17 => new \Hoa\Compiler\Llk\Rule\Token(17, 'T_SCALAR', null, -1, true),
                18 => new \Hoa\Compiler\Llk\Rule\Token(18, 'T_DIRECTIVE', null, -1, true),
                19 => new \Hoa\Compiler\Llk\Rule\Token(19, 'T_INPUT', null, -1, true),
                20 => new \Hoa\Compiler\Llk\Rule\Token(20, 'T_EXTEND', null, -1, true),
                'Keyword' => new \Hoa\Compiler\Llk\Rule\Choice('Keyword', [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20], null),
                'Number' => new \Hoa\Compiler\Llk\Rule\Token('Number', 'T_NUMBER_VALUE', null, -1, true),
                'Nullable' => new \Hoa\Compiler\Llk\Rule\Token('Nullable', 'T_NULL', null, -1, true),
                24 => new \Hoa\Compiler\Llk\Rule\Token(24, 'T_BOOL_TRUE', null, -1, true),
                25 => new \Hoa\Compiler\Llk\Rule\Token(25, 'T_BOOL_FALSE', null, -1, true),
                'Boolean' => new \Hoa\Compiler\Llk\Rule\Choice('Boolean', [24, 25], null),
                27 => new \Hoa\Compiler\Llk\Rule\Token(27, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                28 => new \Hoa\Compiler\Llk\Rule\Token(28, 'T_MULTILINE_STRING', null, -1, true),
                29 => new \Hoa\Compiler\Llk\Rule\Token(29, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                30 => new \Hoa\Compiler\Llk\Rule\Concatenation(30, [27, 28, 29], null),
                31 => new \Hoa\Compiler\Llk\Rule\Token(31, 'T_STRING_OPEN', null, -1, false),
                32 => new \Hoa\Compiler\Llk\Rule\Token(32, 'T_STRING', null, -1, true),
                33 => new \Hoa\Compiler\Llk\Rule\Token(33, 'T_STRING_CLOSE', null, -1, false),
                34 => new \Hoa\Compiler\Llk\Rule\Concatenation(34, [31, 32, 33], null),
                'String' => new \Hoa\Compiler\Llk\Rule\Choice('String', [30, 34], null),
                'Relation' => new \Hoa\Compiler\Llk\Rule\Token('Relation', 'T_NAME', null, -1, true),
                37 => new \Hoa\Compiler\Llk\Rule\Choice(37, ['Scalar', 'ValueKeyword', 'Relation'], null),
                'Name' => new \Hoa\Compiler\Llk\Rule\Concatenation('Name', [37], '#Name'),
                39 => new \Hoa\Compiler\Llk\Rule\Choice(39, ['Scalar', 'Keyword', 'ValueKeyword', 'Relation'], null),
                'Key' => new \Hoa\Compiler\Llk\Rule\Concatenation('Key', [39], '#Name'),
                41 => new \Hoa\Compiler\Llk\Rule\Choice(41, ['String', 'Number', 'Nullable', 'Keyword', 'Scalar', 'Relation', 'Object', 'List', 'ValueKeyword'], null),
                'Value' => new \Hoa\Compiler\Llk\Rule\Concatenation('Value', [41], '#Value'),
                'ValueDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueDefinition', ['ValueDefinitionResolver'], null),
                44 => new \Hoa\Compiler\Llk\Rule\Token(44, 'T_NON_NULL', null, -1, true),
                45 => new \Hoa\Compiler\Llk\Rule\Repetition(45, 0, 1, 44, null),
                46 => new \Hoa\Compiler\Llk\Rule\Concatenation(46, ['ValueListDefinition', 45], '#List'),
                47 => new \Hoa\Compiler\Llk\Rule\Token(47, 'T_NON_NULL', null, -1, true),
                48 => new \Hoa\Compiler\Llk\Rule\Repetition(48, 0, 1, 47, null),
                49 => new \Hoa\Compiler\Llk\Rule\Concatenation(49, ['ValueScalarDefinition', 48], '#Type'),
                'ValueDefinitionResolver' => new \Hoa\Compiler\Llk\Rule\Choice('ValueDefinitionResolver', [46, 49], null),
                51 => new \Hoa\Compiler\Llk\Rule\Token(51, 'T_BRACKET_OPEN', null, -1, false),
                52 => new \Hoa\Compiler\Llk\Rule\Token(52, 'T_NON_NULL', null, -1, true),
                53 => new \Hoa\Compiler\Llk\Rule\Repetition(53, 0, 1, 52, null),
                54 => new \Hoa\Compiler\Llk\Rule\Concatenation(54, ['ValueScalarDefinition', 53], '#Type'),
                55 => new \Hoa\Compiler\Llk\Rule\Token(55, 'T_BRACKET_CLOSE', null, -1, false),
                'ValueListDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ValueListDefinition', [51, 54, 55], null),
                57 => new \Hoa\Compiler\Llk\Rule\Token(57, 'T_NAME', null, -1, true),
                'ValueScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Choice('ValueScalarDefinition', ['Keyword', 'Scalar', 57], null),
                59 => new \Hoa\Compiler\Llk\Rule\Token(59, 'T_BRACE_OPEN', null, -1, false),
                60 => new \Hoa\Compiler\Llk\Rule\Repetition(60, 0, -1, 'ObjectPair', null),
                61 => new \Hoa\Compiler\Llk\Rule\Token(61, 'T_BRACE_CLOSE', null, -1, false),
                'Object' => new \Hoa\Compiler\Llk\Rule\Concatenation('Object', [59, 60, 61], '#Object'),
                63 => new \Hoa\Compiler\Llk\Rule\Token(63, 'T_COLON', null, -1, false),
                'ObjectPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectPair', ['Key', 63, 'Value'], '#ObjectPair'),
                65 => new \Hoa\Compiler\Llk\Rule\Token(65, 'T_BRACKET_OPEN', null, -1, false),
                66 => new \Hoa\Compiler\Llk\Rule\Repetition(66, 0, -1, 'Value', null),
                67 => new \Hoa\Compiler\Llk\Rule\Token(67, 'T_BRACKET_CLOSE', null, -1, false),
                'List' => new \Hoa\Compiler\Llk\Rule\Concatenation('List', [65, 66, 67], '#List'),
                69 => new \Hoa\Compiler\Llk\Rule\Token(69, 'T_MULTILINE_STRING_OPEN', null, -1, false),
                70 => new \Hoa\Compiler\Llk\Rule\Token(70, 'T_MULTILINE_STRING', null, -1, true),
                71 => new \Hoa\Compiler\Llk\Rule\Token(71, 'T_MULTILINE_STRING_CLOSE', null, -1, false),
                72 => new \Hoa\Compiler\Llk\Rule\Concatenation(72, [69, 70, 71], null),
                'Documentation' => new \Hoa\Compiler\Llk\Rule\Concatenation('Documentation', [72], '#Description'),
                74 => new \Hoa\Compiler\Llk\Rule\Repetition(74, 0, 1, 'Documentation', null),
                75 => new \Hoa\Compiler\Llk\Rule\Token(75, 'T_SCHEMA', null, -1, false),
                76 => new \Hoa\Compiler\Llk\Rule\Repetition(76, 0, -1, 'Directive', null),
                77 => new \Hoa\Compiler\Llk\Rule\Token(77, 'T_BRACE_OPEN', null, -1, false),
                78 => new \Hoa\Compiler\Llk\Rule\Token(78, 'T_BRACE_CLOSE', null, -1, false),
                'SchemaDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinition', [74, 75, 76, 77, 'SchemaDefinitionBody', 78], '#SchemaDefinition'),
                80 => new \Hoa\Compiler\Llk\Rule\Choice(80, ['SchemaDefinitionMutation', 'SchemaDefinitionSubscription'], null),
                81 => new \Hoa\Compiler\Llk\Rule\Repetition(81, 0, -1, 80, null),
                82 => new \Hoa\Compiler\Llk\Rule\Choice(82, ['SchemaDefinitionMutation', 'SchemaDefinitionSubscription'], null),
                83 => new \Hoa\Compiler\Llk\Rule\Repetition(83, 0, -1, 82, null),
                'SchemaDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionBody', [81, 'SchemaDefinitionQuery', 83], null),
                85 => new \Hoa\Compiler\Llk\Rule\Repetition(85, 0, 1, 'Documentation', null),
                86 => new \Hoa\Compiler\Llk\Rule\Token(86, 'T_SCHEMA_QUERY', null, -1, false),
                87 => new \Hoa\Compiler\Llk\Rule\Token(87, 'T_COLON', null, -1, false),
                'SchemaDefinitionQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionQuery', [85, 86, 87, 'SchemaDefinitionFieldValue'], '#Query'),
                89 => new \Hoa\Compiler\Llk\Rule\Repetition(89, 0, 1, 'Documentation', null),
                90 => new \Hoa\Compiler\Llk\Rule\Token(90, 'T_SCHEMA_MUTATION', null, -1, false),
                91 => new \Hoa\Compiler\Llk\Rule\Token(91, 'T_COLON', null, -1, false),
                'SchemaDefinitionMutation' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionMutation', [89, 90, 91, 'SchemaDefinitionFieldValue'], '#Mutation'),
                93 => new \Hoa\Compiler\Llk\Rule\Repetition(93, 0, 1, 'Documentation', null),
                94 => new \Hoa\Compiler\Llk\Rule\Token(94, 'T_SCHEMA_SUBSCRIPTION', null, -1, false),
                95 => new \Hoa\Compiler\Llk\Rule\Token(95, 'T_COLON', null, -1, false),
                'SchemaDefinitionSubscription' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionSubscription', [93, 94, 95, 'SchemaDefinitionFieldValue'], '#Subscription'),
                97 => new \Hoa\Compiler\Llk\Rule\Repetition(97, 0, -1, 'Directive', null),
                'SchemaDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('SchemaDefinitionFieldValue', ['ValueDefinition', 97], null),
                99 => new \Hoa\Compiler\Llk\Rule\Repetition(99, 0, 1, 'Documentation', null),
                100 => new \Hoa\Compiler\Llk\Rule\Token(100, 'T_SCALAR', null, -1, false),
                101 => new \Hoa\Compiler\Llk\Rule\Repetition(101, 0, -1, 'Directive', null),
                'ScalarDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ScalarDefinition', [99, 100, 'Name', 101], '#ScalarDefinition'),
                103 => new \Hoa\Compiler\Llk\Rule\Repetition(103, 0, 1, 'Documentation', null),
                104 => new \Hoa\Compiler\Llk\Rule\Token(104, 'T_INPUT', null, -1, false),
                105 => new \Hoa\Compiler\Llk\Rule\Repetition(105, 0, -1, 'Directive', null),
                106 => new \Hoa\Compiler\Llk\Rule\Token(106, 'T_BRACE_OPEN', null, -1, false),
                107 => new \Hoa\Compiler\Llk\Rule\Repetition(107, 1, -1, 'InputDefinitionField', null),
                108 => new \Hoa\Compiler\Llk\Rule\Token(108, 'T_BRACE_CLOSE', null, -1, false),
                'InputDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinition', [103, 104, 'Name', 105, 106, 107, 108], '#InputDefinition'),
                110 => new \Hoa\Compiler\Llk\Rule\Repetition(110, 0, 1, 'Documentation', null),
                111 => new \Hoa\Compiler\Llk\Rule\Token(111, 'T_COLON', null, -1, false),
                112 => new \Hoa\Compiler\Llk\Rule\Repetition(112, 0, 1, 'InputDefinitionDefaultValue', null),
                113 => new \Hoa\Compiler\Llk\Rule\Repetition(113, 0, -1, 'Directive', null),
                114 => new \Hoa\Compiler\Llk\Rule\Concatenation(114, ['Key', 111, 'ValueDefinition', 112, 113], null),
                'InputDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionField', [110, 114], '#Field'),
                116 => new \Hoa\Compiler\Llk\Rule\Token(116, 'T_EQUAL', null, -1, false),
                'InputDefinitionDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('InputDefinitionDefaultValue', [116, 'Value'], '#DefaultValue'),
                118 => new \Hoa\Compiler\Llk\Rule\Repetition(118, 0, 1, 'Documentation', null),
                119 => new \Hoa\Compiler\Llk\Rule\Token(119, 'T_EXTEND', null, -1, false),
                'ExtendDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ExtendDefinition', [118, 119, 'ObjectDefinition'], '#ExtendDefinition'),
                121 => new \Hoa\Compiler\Llk\Rule\Repetition(121, 0, 1, 'Documentation', null),
                122 => new \Hoa\Compiler\Llk\Rule\Token(122, 'T_DIRECTIVE', null, -1, false),
                123 => new \Hoa\Compiler\Llk\Rule\Token(123, 'T_DIRECTIVE_AT', null, -1, false),
                124 => new \Hoa\Compiler\Llk\Rule\Repetition(124, 0, -1, 'DirectiveDefinitionArguments', null),
                125 => new \Hoa\Compiler\Llk\Rule\Token(125, 'T_ON', null, -1, false),
                126 => new \Hoa\Compiler\Llk\Rule\Repetition(126, 1, -1, 'DirectiveDefinitionTargets', null),
                'DirectiveDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinition', [121, 122, 123, 'Name', 124, 125, 126], '#DirectiveDefinition'),
                128 => new \Hoa\Compiler\Llk\Rule\Token(128, 'T_PARENTHESIS_OPEN', null, -1, false),
                129 => new \Hoa\Compiler\Llk\Rule\Repetition(129, 0, -1, 'DirectiveDefinitionArgument', null),
                130 => new \Hoa\Compiler\Llk\Rule\Token(130, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveDefinitionArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArguments', [128, 129, 130], '#Argument'),
                132 => new \Hoa\Compiler\Llk\Rule\Repetition(132, 0, 1, 'Documentation', null),
                133 => new \Hoa\Compiler\Llk\Rule\Token(133, 'T_COLON', null, -1, false),
                'DirectiveDefinitionArgument' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionArgument', [132, 'Key', 133, 'ValueDefinition'], null),
                135 => new \Hoa\Compiler\Llk\Rule\Token(135, 'T_OR', null, -1, false),
                136 => new \Hoa\Compiler\Llk\Rule\Concatenation(136, [135, 'Key'], null),
                137 => new \Hoa\Compiler\Llk\Rule\Repetition(137, 0, -1, 136, null),
                'DirectiveDefinitionTargets' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveDefinitionTargets', ['Key', 137], '#Target'),
                139 => new \Hoa\Compiler\Llk\Rule\Repetition(139, 0, 1, 'Documentation', null),
                140 => new \Hoa\Compiler\Llk\Rule\Token(140, 'T_TYPE', null, -1, false),
                141 => new \Hoa\Compiler\Llk\Rule\Repetition(141, 0, 1, 'ObjectDefinitionImplements', null),
                142 => new \Hoa\Compiler\Llk\Rule\Repetition(142, 0, -1, 'Directive', null),
                143 => new \Hoa\Compiler\Llk\Rule\Token(143, 'T_BRACE_OPEN', null, -1, false),
                144 => new \Hoa\Compiler\Llk\Rule\Repetition(144, 0, -1, 'ObjectDefinitionField', null),
                145 => new \Hoa\Compiler\Llk\Rule\Token(145, 'T_BRACE_CLOSE', null, -1, false),
                'ObjectDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinition', [139, 140, 'Name', 141, 142, 143, 144, 145], '#ObjectDefinition'),
                147 => new \Hoa\Compiler\Llk\Rule\Token(147, 'T_TYPE_IMPLEMENTS', null, -1, false),
                148 => new \Hoa\Compiler\Llk\Rule\Repetition(148, 1, -1, 'Key', null),
                'ObjectDefinitionImplements' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionImplements', [147, 148], '#Implements'),
                150 => new \Hoa\Compiler\Llk\Rule\Repetition(150, 0, 1, 'Documentation', null),
                151 => new \Hoa\Compiler\Llk\Rule\Repetition(151, 0, 1, 'Arguments', null),
                152 => new \Hoa\Compiler\Llk\Rule\Token(152, 'T_COLON', null, -1, false),
                153 => new \Hoa\Compiler\Llk\Rule\Concatenation(153, ['Key', 151, 152, 'ObjectDefinitionFieldValue'], null),
                'ObjectDefinitionField' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionField', [150, 153], '#Field'),
                155 => new \Hoa\Compiler\Llk\Rule\Repetition(155, 0, -1, 'Directive', null),
                'ObjectDefinitionFieldValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ObjectDefinitionFieldValue', ['ValueDefinition', 155], null),
                157 => new \Hoa\Compiler\Llk\Rule\Repetition(157, 0, 1, 'Documentation', null),
                158 => new \Hoa\Compiler\Llk\Rule\Token(158, 'T_INTERFACE', null, -1, false),
                159 => new \Hoa\Compiler\Llk\Rule\Repetition(159, 0, -1, 'Directive', null),
                160 => new \Hoa\Compiler\Llk\Rule\Token(160, 'T_BRACE_OPEN', null, -1, false),
                161 => new \Hoa\Compiler\Llk\Rule\Repetition(161, 0, -1, 'InterfaceDefinitionBody', null),
                162 => new \Hoa\Compiler\Llk\Rule\Token(162, 'T_BRACE_CLOSE', null, -1, false),
                'InterfaceDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinition', [157, 158, 'Name', 159, 160, 161, 162], '#InterfaceDefinition'),
                164 => new \Hoa\Compiler\Llk\Rule\Token(164, 'T_COLON', null, -1, false),
                165 => new \Hoa\Compiler\Llk\Rule\Repetition(165, 0, -1, 'Directive', null),
                166 => new \Hoa\Compiler\Llk\Rule\Concatenation(166, ['InterfaceDefinitionFieldKey', 164, 'ValueDefinition', 165], null),
                'InterfaceDefinitionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionBody', [166], '#Field'),
                168 => new \Hoa\Compiler\Llk\Rule\Repetition(168, 0, 1, 'Documentation', null),
                169 => new \Hoa\Compiler\Llk\Rule\Repetition(169, 0, 1, 'Arguments', null),
                'InterfaceDefinitionFieldKey' => new \Hoa\Compiler\Llk\Rule\Concatenation('InterfaceDefinitionFieldKey', [168, 'Key', 169], null),
                171 => new \Hoa\Compiler\Llk\Rule\Repetition(171, 0, 1, 'Documentation', null),
                172 => new \Hoa\Compiler\Llk\Rule\Token(172, 'T_ENUM', null, -1, false),
                173 => new \Hoa\Compiler\Llk\Rule\Repetition(173, 0, -1, 'Directive', null),
                174 => new \Hoa\Compiler\Llk\Rule\Token(174, 'T_BRACE_OPEN', null, -1, false),
                175 => new \Hoa\Compiler\Llk\Rule\Repetition(175, 1, -1, 'EnumField', null),
                176 => new \Hoa\Compiler\Llk\Rule\Token(176, 'T_BRACE_CLOSE', null, -1, false),
                'EnumDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumDefinition', [171, 172, 'Name', 173, 174, 175, 176], '#EnumDefinition'),
                178 => new \Hoa\Compiler\Llk\Rule\Repetition(178, 0, 1, 'Documentation', null),
                179 => new \Hoa\Compiler\Llk\Rule\Repetition(179, 0, -1, 'Directive', null),
                180 => new \Hoa\Compiler\Llk\Rule\Concatenation(180, ['EnumValue', 179], null),
                'EnumField' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumField', [178, 180], '#Value'),
                182 => new \Hoa\Compiler\Llk\Rule\Choice(182, ['Scalar', 'Keyword', 'Relation'], null),
                'EnumValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('EnumValue', [182], '#Name'),
                184 => new \Hoa\Compiler\Llk\Rule\Repetition(184, 0, 1, 'Documentation', null),
                185 => new \Hoa\Compiler\Llk\Rule\Token(185, 'T_UNION', null, -1, false),
                186 => new \Hoa\Compiler\Llk\Rule\Repetition(186, 0, -1, 'Directive', null),
                187 => new \Hoa\Compiler\Llk\Rule\Token(187, 'T_EQUAL', null, -1, false),
                'UnionDefinition' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionDefinition', [184, 185, 'Name', 186, 187, 'UnionBody'], '#UnionDefinition'),
                189 => new \Hoa\Compiler\Llk\Rule\Token(189, 'T_OR', null, -1, false),
                190 => new \Hoa\Compiler\Llk\Rule\Repetition(190, 0, 1, 189, null),
                191 => new \Hoa\Compiler\Llk\Rule\Repetition(191, 1, -1, 'UnionUnitesList', null),
                'UnionBody' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionBody', [190, 191], '#Relations'),
                193 => new \Hoa\Compiler\Llk\Rule\Token(193, 'T_OR', null, -1, false),
                194 => new \Hoa\Compiler\Llk\Rule\Concatenation(194, [193, 'Name'], null),
                195 => new \Hoa\Compiler\Llk\Rule\Repetition(195, 0, -1, 194, null),
                'UnionUnitesList' => new \Hoa\Compiler\Llk\Rule\Concatenation('UnionUnitesList', ['Name', 195], null),
                197 => new \Hoa\Compiler\Llk\Rule\Token(197, 'T_PARENTHESIS_OPEN', null, -1, false),
                198 => new \Hoa\Compiler\Llk\Rule\Repetition(198, 0, -1, 'ArgumentPair', null),
                199 => new \Hoa\Compiler\Llk\Rule\Token(199, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'Arguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('Arguments', [197, 198, 199], null),
                201 => new \Hoa\Compiler\Llk\Rule\Repetition(201, 0, 1, 'Documentation', null),
                202 => new \Hoa\Compiler\Llk\Rule\Token(202, 'T_COLON', null, -1, false),
                203 => new \Hoa\Compiler\Llk\Rule\Repetition(203, 0, 1, 'ArgumentDefaultValue', null),
                204 => new \Hoa\Compiler\Llk\Rule\Repetition(204, 0, -1, 'Directive', null),
                'ArgumentPair' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentPair', [201, 'Key', 202, 'ValueDefinition', 203, 204], '#Argument'),
                'ArgumentValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentValue', ['ValueDefinition'], '#Type'),
                207 => new \Hoa\Compiler\Llk\Rule\Token(207, 'T_EQUAL', null, -1, false),
                'ArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('ArgumentDefaultValue', [207, 'Value'], null),
                209 => new \Hoa\Compiler\Llk\Rule\Token(209, 'T_DIRECTIVE_AT', null, -1, false),
                210 => new \Hoa\Compiler\Llk\Rule\Repetition(210, 0, 1, 'DirectiveArguments', null),
                'Directive' => new \Hoa\Compiler\Llk\Rule\Concatenation('Directive', [209, 'Name', 210], '#Directive'),
                212 => new \Hoa\Compiler\Llk\Rule\Token(212, 'T_PARENTHESIS_OPEN', null, -1, false),
                213 => new \Hoa\Compiler\Llk\Rule\Repetition(213, 0, -1, 'DirectivePair', null),
                214 => new \Hoa\Compiler\Llk\Rule\Token(214, 'T_PARENTHESIS_CLOSE', null, -1, false),
                'DirectiveArguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArguments', [212, 213, 214], null),
                216 => new \Hoa\Compiler\Llk\Rule\Token(216, 'T_COLON', null, -1, false),
                217 => new \Hoa\Compiler\Llk\Rule\Repetition(217, 0, 1, 'DirectiveArgumentDefaultValue', null),
                'DirectivePair' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectivePair', ['Key', 216, 'Value', 217], '#Argument'),
                219 => new \Hoa\Compiler\Llk\Rule\Token(219, 'T_EQUAL', null, -1, false),
                'DirectiveArgumentDefaultValue' => new \Hoa\Compiler\Llk\Rule\Concatenation('DirectiveArgumentDefaultValue', [219, 'Value'], null),
            ],
            [
            ]
        );

        $this->getRule('Document')->setDefaultId('#Document');
        $this->getRule('Document')->setPPRepresentation(' Definitions()*');
        $this->getRule('Definitions')->setPPRepresentation(' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()');
        $this->getRule('Scalar')->setPPRepresentation(' <T_NAME>');
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
        $this->getRule('InputDefinitionField')->setPPRepresentation(' Documentation()? ( Key() ::T_COLON:: ValueDefinition() InputDefinitionDefaultValue()? Directive()* ) #Field');
        $this->getRule('InputDefinitionDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value() #DefaultValue');
        $this->getRule('ExtendDefinition')->setDefaultId('#ExtendDefinition');
        $this->getRule('ExtendDefinition')->setPPRepresentation(' Documentation()? ::T_EXTEND:: ObjectDefinition()');
        $this->getRule('DirectiveDefinition')->setDefaultId('#DirectiveDefinition');
        $this->getRule('DirectiveDefinition')->setPPRepresentation(' Documentation()? ::T_DIRECTIVE:: ::T_DIRECTIVE_AT:: Name() DirectiveDefinitionArguments()* ::T_ON:: DirectiveDefinitionTargets()+');
        $this->getRule('DirectiveDefinitionArguments')->setPPRepresentation(' ::T_PARENTHESIS_OPEN:: DirectiveDefinitionArgument()* ::T_PARENTHESIS_CLOSE:: #Argument');
        $this->getRule('DirectiveDefinitionArgument')->setPPRepresentation(' Documentation()? Key() ::T_COLON:: ValueDefinition()');
        $this->getRule('DirectiveDefinitionTargets')->setPPRepresentation(' Key() (::T_OR:: Key())* #Target');
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
        $this->getRule('EnumValue')->setPPRepresentation(' ( Scalar() | Keyword() | Relation() ) #Name');
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
        $this->getRule('DirectivePair')->setPPRepresentation(' Key() ::T_COLON:: Value() DirectiveArgumentDefaultValue()? #Argument');
        $this->getRule('DirectiveArgumentDefaultValue')->setPPRepresentation(' ::T_EQUAL:: Value()');
    }
}
