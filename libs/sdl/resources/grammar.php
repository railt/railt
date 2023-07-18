<?php

use Railt\SDL\Node;
use Railt\SDL\Node\Expression as Expr;
use Railt\SDL\Node\Statement as Stmt;

return [
    'initial' => 0,
    'tokens' => [
        'default' => [
            'T_AND' => '&',
            'T_OR' => '\\|',
            'T_PARENTHESIS_OPEN' => '\\(',
            'T_PARENTHESIS_CLOSE' => '\\)',
            'T_BRACKET_OPEN' => '\\[',
            'T_BRACKET_CLOSE' => '\\]',
            'T_BRACE_OPEN' => '{',
            'T_BRACE_CLOSE' => '}',
            'T_NON_NULL' => '!',
            'T_EQUAL' => '=',
            'T_DIRECTIVE_AT' => '@',
            'T_COLON' => ':',
            'T_COMMA' => ',',
            'T_FLOAT_EXP' => '\\-?(?:0|[1-9][0-9]*)(?:[eE][\\+\\-]?[0-9]+)',
            'T_FLOAT' => '\\-?(?:0|[1-9][0-9]*)(?:\\.[0-9]+)(?:[eE][\\+\\-]?[0-9]+)?',
            'T_INT' => '\\-?(?:0|[1-9][0-9]*)',
            'T_TRUE' => '(?<=\\b)true\\b',
            'T_FALSE' => '(?<=\\b)false\\b',
            'T_NULL' => '(?<=\\b)null\\b',
            'T_BLOCK_STRING' => '"""((?:\\\\"|(?!""").)*)"""',
            'T_STRING' => '"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"',
            'T_TYPE' => '(?<=\\b)type\\b',
            'T_ENUM' => '(?<=\\b)enum\\b',
            'T_UNION' => '(?<=\\b)union\\b',
            'T_INTERFACE' => '(?<=\\b)interface\\b',
            'T_SCHEMA' => '(?<=\\b)schema\\b',
            'T_SCALAR' => '(?<=\\b)scalar\\b',
            'T_DIRECTIVE' => '(?<=\\b)directive\\b',
            'T_INPUT' => '(?<=\\b)input\\b',
            'T_QUERY' => '(?<=\\b)query\\b',
            'T_MUTATION' => '(?<=\\b)mutation\\b',
            'T_ON' => '(?<=\\b)on\\b',
            'T_SUBSCRIPTION' => '(?<=\\b)subscription\\b',
            'T_EXTEND' => '(?<=\\b)extend\\b',
            'T_EXTENDS' => '(?<=\\b)extends\\b',
            'T_IMPLEMENTS' => '(?<=\\b)implements\\b',
            'T_REPEATABLE' => '(?<=\\b)repeatable\\b',
            'T_VARIABLE' => '\\$([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)',
            'T_NAME' => '[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*',
            'T_COMMENT' => '#[^\\n]*',
            'T_WHITESPACE' => '\\s+',
        ],
    ],
    'skip' => [
        'T_COMMENT',
        'T_WHITESPACE',
    ],
    'transitions' => [
        
    ],
    'grammar' => [
        0 => new \Phplrt\Parser\Grammar\Repetition(75, 0, INF),
        1 => new \Phplrt\Parser\Grammar\Alternation([72, 73]),
        2 => new \Phplrt\Parser\Grammar\Optional(1),
        3 => new \Phplrt\Parser\Grammar\Alternation([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]),
        4 => new \Phplrt\Parser\Grammar\Concatenation([3]),
        5 => new \Phplrt\Parser\Grammar\Lexeme('T_TRUE', true),
        6 => new \Phplrt\Parser\Grammar\Lexeme('T_FALSE', true),
        7 => new \Phplrt\Parser\Grammar\Lexeme('T_NULL', true),
        8 => new \Phplrt\Parser\Grammar\Lexeme('T_TYPE', true),
        9 => new \Phplrt\Parser\Grammar\Lexeme('T_ENUM', true),
        10 => new \Phplrt\Parser\Grammar\Lexeme('T_UNION', true),
        11 => new \Phplrt\Parser\Grammar\Lexeme('T_INTERFACE', true),
        12 => new \Phplrt\Parser\Grammar\Lexeme('T_SCHEMA', true),
        13 => new \Phplrt\Parser\Grammar\Lexeme('T_SCALAR', true),
        14 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE', true),
        15 => new \Phplrt\Parser\Grammar\Lexeme('T_INPUT', true),
        16 => new \Phplrt\Parser\Grammar\Lexeme('T_QUERY', true),
        17 => new \Phplrt\Parser\Grammar\Lexeme('T_MUTATION', true),
        18 => new \Phplrt\Parser\Grammar\Lexeme('T_ON', true),
        19 => new \Phplrt\Parser\Grammar\Lexeme('T_SUBSCRIPTION', true),
        20 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', true),
        21 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTENDS', true),
        22 => new \Phplrt\Parser\Grammar\Lexeme('T_IMPLEMENTS', true),
        23 => new \Phplrt\Parser\Grammar\Lexeme('T_REPEATABLE', true),
        24 => new \Phplrt\Parser\Grammar\Lexeme('T_NAME', true),
        25 => new \Phplrt\Parser\Grammar\Concatenation([31, 32]),
        26 => new \Phplrt\Parser\Grammar\Concatenation([29, 28, 30]),
        27 => new \Phplrt\Parser\Grammar\Concatenation([4]),
        28 => new \Phplrt\Parser\Grammar\Alternation([25, 26, 27]),
        29 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_OPEN', false),
        30 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        31 => new \Phplrt\Parser\Grammar\Alternation([26, 27]),
        32 => new \Phplrt\Parser\Grammar\Lexeme('T_NON_NULL', false),
        33 => new \Phplrt\Parser\Grammar\Concatenation([36, 3, 37]),
        34 => new \Phplrt\Parser\Grammar\Repetition(33, 0, INF),
        35 => new \Phplrt\Parser\Grammar\Concatenation([42, 43, 44]),
        36 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        37 => new \Phplrt\Parser\Grammar\Optional(35),
        38 => new \Phplrt\Parser\Grammar\Concatenation([3, 46, 45]),
        39 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        40 => new \Phplrt\Parser\Grammar\Optional(39),
        41 => new \Phplrt\Parser\Grammar\Concatenation([38, 40]),
        42 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        43 => new \Phplrt\Parser\Grammar\Repetition(41, 0, INF),
        44 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        45 => new \Phplrt\Parser\Grammar\Alternation([74, 54, 53, 1, 49, 62, 50, 61, 70]),
        46 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        47 => new \Phplrt\Parser\Grammar\Lexeme('T_FALSE', true),
        48 => new \Phplrt\Parser\Grammar\Lexeme('T_TRUE', true),
        49 => new \Phplrt\Parser\Grammar\Alternation([47, 48]),
        50 => new \Phplrt\Parser\Grammar\Concatenation([3]),
        51 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT', true),
        52 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT_EXP', true),
        53 => new \Phplrt\Parser\Grammar\Alternation([51, 52]),
        54 => new \Phplrt\Parser\Grammar\Lexeme('T_INT', true),
        55 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        56 => new \Phplrt\Parser\Grammar\Optional(55),
        57 => new \Phplrt\Parser\Grammar\Concatenation([45, 56]),
        58 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_OPEN', false),
        59 => new \Phplrt\Parser\Grammar\Repetition(57, 0, INF),
        60 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        61 => new \Phplrt\Parser\Grammar\Concatenation([58, 59, 60]),
        62 => new \Phplrt\Parser\Grammar\Lexeme('T_NULL', true),
        63 => new \Phplrt\Parser\Grammar\Concatenation([3, 71, 45]),
        64 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        65 => new \Phplrt\Parser\Grammar\Optional(64),
        66 => new \Phplrt\Parser\Grammar\Concatenation([63, 65]),
        67 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        68 => new \Phplrt\Parser\Grammar\Repetition(66, 0, INF),
        69 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        70 => new \Phplrt\Parser\Grammar\Concatenation([67, 68, 69]),
        71 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        72 => new \Phplrt\Parser\Grammar\Lexeme('T_BLOCK_STRING', true),
        73 => new \Phplrt\Parser\Grammar\Lexeme('T_STRING', true),
        74 => new \Phplrt\Parser\Grammar\Lexeme('T_VARIABLE', true),
        75 => new \Phplrt\Parser\Grammar\Alternation([76, 77]),
        76 => new \Phplrt\Parser\Grammar\Alternation([108, 127, 205]),
        78 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        79 => new \Phplrt\Parser\Grammar\Concatenation([78, 45]),
        80 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        81 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        82 => new \Phplrt\Parser\Grammar\Optional(79),
        83 => new \Phplrt\Parser\Grammar\Optional(80),
        84 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 81, 28, 82, 34, 83]),
        85 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 34, 88]),
        86 => new \Phplrt\Parser\Grammar\Repetition(85, 0, INF),
        87 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        88 => new \Phplrt\Parser\Grammar\Optional(87),
        89 => new \Phplrt\Parser\Grammar\Repetition(91, 1, INF),
        90 => new \Phplrt\Parser\Grammar\Optional(89),
        91 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 92, 94, 28, 34, 95]),
        92 => new \Phplrt\Parser\Grammar\Optional(96),
        93 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        94 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        95 => new \Phplrt\Parser\Grammar\Optional(93),
        96 => new \Phplrt\Parser\Grammar\Concatenation([97, 98, 99]),
        97 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        98 => new \Phplrt\Parser\Grammar\Repetition(84, 0, INF),
        99 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        100 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        101 => new \Phplrt\Parser\Grammar\Concatenation([100, 45]),
        102 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        103 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        104 => new \Phplrt\Parser\Grammar\Optional(101),
        105 => new \Phplrt\Parser\Grammar\Optional(102),
        106 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 103, 28, 104, 34, 105]),
        107 => new \Phplrt\Parser\Grammar\Concatenation([109, 110]),
        108 => new \Phplrt\Parser\Grammar\Concatenation([2, 107]),
        109 => new \Phplrt\Parser\Grammar\Concatenation([111, 34]),
        110 => new \Phplrt\Parser\Grammar\Optional(112),
        111 => new \Phplrt\Parser\Grammar\Lexeme('T_SCHEMA', false),
        112 => new \Phplrt\Parser\Grammar\Concatenation([117, 118, 119]),
        113 => new \Phplrt\Parser\Grammar\Concatenation([2, 120, 121, 27, 34]),
        114 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        115 => new \Phplrt\Parser\Grammar\Optional(114),
        116 => new \Phplrt\Parser\Grammar\Concatenation([113, 115]),
        117 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        118 => new \Phplrt\Parser\Grammar\Repetition(116, 0, INF),
        119 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        120 => new \Phplrt\Parser\Grammar\Alternation([122, 123, 124]),
        121 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        122 => new \Phplrt\Parser\Grammar\Lexeme('T_QUERY', true),
        123 => new \Phplrt\Parser\Grammar\Lexeme('T_MUTATION', true),
        124 => new \Phplrt\Parser\Grammar\Lexeme('T_SUBSCRIPTION', true),
        125 => new \Phplrt\Parser\Grammar\Concatenation([130, 131, 3, 128, 129]),
        126 => new \Phplrt\Parser\Grammar\Concatenation([138, 137]),
        127 => new \Phplrt\Parser\Grammar\Concatenation([2, 125, 126]),
        128 => new \Phplrt\Parser\Grammar\Optional(132),
        129 => new \Phplrt\Parser\Grammar\Optional(136),
        130 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE', false),
        131 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        132 => new \Phplrt\Parser\Grammar\Concatenation([133, 134, 135]),
        133 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        134 => new \Phplrt\Parser\Grammar\Repetition(84, 0, INF),
        135 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        136 => new \Phplrt\Parser\Grammar\Lexeme('T_REPEATABLE', true),
        137 => new \Phplrt\Parser\Grammar\Concatenation([142, 3, 143]),
        138 => new \Phplrt\Parser\Grammar\Lexeme('T_ON', false),
        139 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        140 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        141 => new \Phplrt\Parser\Grammar\Concatenation([140, 3]),
        142 => new \Phplrt\Parser\Grammar\Optional(139),
        143 => new \Phplrt\Parser\Grammar\Repetition(141, 0, INF),
        144 => new \Phplrt\Parser\Grammar\Concatenation([146, 147]),
        145 => new \Phplrt\Parser\Grammar\Concatenation([2, 144]),
        146 => new \Phplrt\Parser\Grammar\Concatenation([148, 3, 34]),
        147 => new \Phplrt\Parser\Grammar\Optional(149),
        148 => new \Phplrt\Parser\Grammar\Lexeme('T_ENUM', false),
        149 => new \Phplrt\Parser\Grammar\Concatenation([150, 151, 152]),
        150 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        151 => new \Phplrt\Parser\Grammar\Repetition(85, 0, INF),
        152 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        153 => new \Phplrt\Parser\Grammar\Concatenation([155, 156]),
        154 => new \Phplrt\Parser\Grammar\Concatenation([2, 153]),
        155 => new \Phplrt\Parser\Grammar\Concatenation([157, 3, 34]),
        156 => new \Phplrt\Parser\Grammar\Optional(158),
        157 => new \Phplrt\Parser\Grammar\Lexeme('T_INPUT', false),
        158 => new \Phplrt\Parser\Grammar\Concatenation([159, 160, 161]),
        159 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        160 => new \Phplrt\Parser\Grammar\Repetition(106, 0, INF),
        161 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        162 => new \Phplrt\Parser\Grammar\Concatenation([164, 165]),
        163 => new \Phplrt\Parser\Grammar\Concatenation([2, 162]),
        164 => new \Phplrt\Parser\Grammar\Concatenation([167, 3, 166, 34]),
        165 => new \Phplrt\Parser\Grammar\Optional(168),
        166 => new \Phplrt\Parser\Grammar\Optional(173),
        167 => new \Phplrt\Parser\Grammar\Lexeme('T_INTERFACE', false),
        168 => new \Phplrt\Parser\Grammar\Concatenation([186, 90, 187]),
        169 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        170 => new \Phplrt\Parser\Grammar\Optional(89),
        171 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        172 => new \Phplrt\Parser\Grammar\Concatenation([169, 170, 171]),
        173 => new \Phplrt\Parser\Grammar\Concatenation([176, 177, 27, 178]),
        174 => new \Phplrt\Parser\Grammar\Alternation([179, 180]),
        175 => new \Phplrt\Parser\Grammar\Concatenation([174, 27]),
        176 => new \Phplrt\Parser\Grammar\Lexeme('T_IMPLEMENTS', false),
        177 => new \Phplrt\Parser\Grammar\Optional(174),
        178 => new \Phplrt\Parser\Grammar\Repetition(175, 0, INF),
        179 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        180 => new \Phplrt\Parser\Grammar\Lexeme('T_AND', false),
        181 => new \Phplrt\Parser\Grammar\Concatenation([183, 184]),
        182 => new \Phplrt\Parser\Grammar\Concatenation([2, 181]),
        183 => new \Phplrt\Parser\Grammar\Concatenation([185, 3, 166, 34]),
        184 => new \Phplrt\Parser\Grammar\Optional(168),
        185 => new \Phplrt\Parser\Grammar\Lexeme('T_TYPE', false),
        186 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        187 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        188 => new \Phplrt\Parser\Grammar\Concatenation([190]),
        189 => new \Phplrt\Parser\Grammar\Concatenation([2, 188]),
        190 => new \Phplrt\Parser\Grammar\Concatenation([191, 3, 34]),
        191 => new \Phplrt\Parser\Grammar\Lexeme('T_SCALAR', false),
        192 => new \Phplrt\Parser\Grammar\Concatenation([194, 195]),
        193 => new \Phplrt\Parser\Grammar\Concatenation([2, 192]),
        194 => new \Phplrt\Parser\Grammar\Concatenation([196, 3, 34]),
        195 => new \Phplrt\Parser\Grammar\Concatenation([199, 198]),
        196 => new \Phplrt\Parser\Grammar\Lexeme('T_UNION', false),
        197 => new \Phplrt\Parser\Grammar\Optional(195),
        198 => new \Phplrt\Parser\Grammar\Concatenation([203, 27, 204]),
        199 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        200 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        201 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        202 => new \Phplrt\Parser\Grammar\Concatenation([201, 27]),
        203 => new \Phplrt\Parser\Grammar\Optional(200),
        204 => new \Phplrt\Parser\Grammar\Repetition(202, 0, INF),
        205 => new \Phplrt\Parser\Grammar\Alternation([189, 182, 163, 193, 145, 154]),
        206 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        207 => new \Phplrt\Parser\Grammar\Concatenation([206, 107]),
        208 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        209 => new \Phplrt\Parser\Grammar\Concatenation([208, 144]),
        210 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        211 => new \Phplrt\Parser\Grammar\Concatenation([210, 153]),
        212 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        213 => new \Phplrt\Parser\Grammar\Concatenation([212, 162]),
        214 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        215 => new \Phplrt\Parser\Grammar\Concatenation([214, 181]),
        216 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        217 => new \Phplrt\Parser\Grammar\Concatenation([216, 188]),
        218 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        219 => new \Phplrt\Parser\Grammar\Concatenation([218, 192]),
        77 => new \Phplrt\Parser\Grammar\Alternation([207, 220]),
        220 => new \Phplrt\Parser\Grammar\Alternation([217, 215, 213, 219, 209, 211])
    ],
    'reducers' => [
        2 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\DescriptionNode($children === [] ? null : $children);
        },
        4 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\NameNode($children[0]->value);
        },
        3 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\IdentifierNode($children->getValue());
        },
        26 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Type\ListTypeNode($children[0]);
        },
        25 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Type\NonNullTypeNode($children[0]);
        },
        27 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Type\NamedTypeNode($children[0]);
        },
        34 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children ?? []);
        },
        33 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\DirectiveNode(
            $children[0],
            isset($children[1]) ? $children[1]->getArrayCopy() : [],
        );
        },
        35 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        38 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\ArgumentNode($children[0], $children[1]);
        },
        49 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\BoolLiteralNode::parse($children->getValue());
        },
        50 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ConstLiteralNode($children[0]);
        },
        53 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\FloatLiteralNode::parse($children->getValue());
        },
        54 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\IntLiteralNode::parse($children->getValue());
        },
        61 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ListLiteralNode($children);
        },
        62 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\NullLiteralNode();
        },
        70 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralNode($children);
        },
        63 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralFieldNode(
            $children[0],
            $children[1],
        );
        },
        72 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\StringLiteralNode::parse(\substr($children->getValue(), 3, -3));
        },
        73 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\StringLiteralNode::parse(\substr($children->getValue(), 1, -1));
        },
        74 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\VariableLiteralNode($children[0]->getValue());
        },
        84 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\ArgumentNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3] instanceof \ArrayObject ? null : $children[3],
            \end($children)->getArrayCopy(),
        );
        },
        86 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        85 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\EnumFieldNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
        );
        },
        90 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        91 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\FieldNode(
            $children[1],
            $children[0],
            $children[3],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
        );
        },
        92 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        106 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\InputFieldNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3] instanceof \ArrayObject ? null : $children[3],
            \end($children)->getArrayCopy(),
        );
        },
        108 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\SchemaDefinitionNode(
            $children[0],
            $children[2]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        },
        110 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        113 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\SchemaFieldNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3]->getArrayCopy(),
        );
        },
        120 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\IdentifierNode($children->getValue());
        },
        127 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\DirectiveDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[3]->getArrayCopy(),
            $children[4]->getArrayCopy(),
        );
        },
        128 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        129 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject(\array_filter([$children]));
        },
        136 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Stmt\Definition\DirectiveDefinition\Modifier::REPEATABLE;
        },
        137 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        145 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\EnumTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        147 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        149 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        154 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InputObjectTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        156 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        158 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        163 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InterfaceTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
            $children[3]->getArrayCopy(),
        );
        },
        165 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        166 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        182 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ObjectTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
            $children[3]->getArrayCopy(),
        );
        },
        184 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        189 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ScalarTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
        );
        },
        193 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\UnionTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        197 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        195 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        207 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\SchemaExtensionNode(
            $children[0]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        },
        209 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\EnumTypeExtensionNode(
            $children[0],
            $children[2]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        },
        211 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\InputObjectTypeExtensionNode(
            $children[0],
            $children[2]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        },
        213 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\InterfaceTypeExtensionNode(
            $children[0],
            $children[1]->getArrayCopy(),
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        215 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\ObjectTypeExtensionNode(
            $children[0],
            $children[1]->getArrayCopy(),
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        217 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\ScalarTypeExtensionNode(
            $children[0],
            $children[1]->getArrayCopy(),
        );
        },
        219 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\UnionTypeExtensionNode(
            $children[0],
            $children[2]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        }
    ]
];