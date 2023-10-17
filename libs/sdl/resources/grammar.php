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
            'T_ANGLE_OPEN' => '<',
            'T_ANGLE_CLOSE' => '>',
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
        0 => new \Phplrt\Parser\Grammar\Repetition(33, 0, INF),
        1 => new \Phplrt\Parser\Grammar\Alternation([62, 63]),
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
        27 => new \Phplrt\Parser\Grammar\Concatenation([3]),
        28 => new \Phplrt\Parser\Grammar\Alternation([25, 26, 27]),
        29 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_OPEN', false),
        30 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        31 => new \Phplrt\Parser\Grammar\Alternation([26, 27]),
        32 => new \Phplrt\Parser\Grammar\Lexeme('T_NON_NULL', false),
        33 => new \Phplrt\Parser\Grammar\Alternation([34, 35]),
        34 => new \Phplrt\Parser\Grammar\Alternation([97, 116, 206]),
        36 => new \Phplrt\Parser\Grammar\Lexeme('T_FALSE', true),
        37 => new \Phplrt\Parser\Grammar\Lexeme('T_TRUE', true),
        38 => new \Phplrt\Parser\Grammar\Alternation([36, 37]),
        39 => new \Phplrt\Parser\Grammar\Concatenation([3]),
        40 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT', true),
        41 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT_EXP', true),
        42 => new \Phplrt\Parser\Grammar\Alternation([40, 41]),
        43 => new \Phplrt\Parser\Grammar\Lexeme('T_INT', true),
        44 => new \Phplrt\Parser\Grammar\Alternation([64, 65]),
        45 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        46 => new \Phplrt\Parser\Grammar\Optional(45),
        47 => new \Phplrt\Parser\Grammar\Concatenation([44, 46]),
        48 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_OPEN', false),
        49 => new \Phplrt\Parser\Grammar\Repetition(47, 0, INF),
        50 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        51 => new \Phplrt\Parser\Grammar\Concatenation([48, 49, 50]),
        52 => new \Phplrt\Parser\Grammar\Lexeme('T_NULL', true),
        53 => new \Phplrt\Parser\Grammar\Concatenation([3, 61, 44]),
        54 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        55 => new \Phplrt\Parser\Grammar\Optional(54),
        56 => new \Phplrt\Parser\Grammar\Concatenation([53, 55]),
        57 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        58 => new \Phplrt\Parser\Grammar\Repetition(56, 0, INF),
        59 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        60 => new \Phplrt\Parser\Grammar\Concatenation([57, 58, 59]),
        61 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        62 => new \Phplrt\Parser\Grammar\Lexeme('T_BLOCK_STRING', true),
        63 => new \Phplrt\Parser\Grammar\Lexeme('T_STRING', true),
        64 => new \Phplrt\Parser\Grammar\Lexeme('T_VARIABLE', true),
        65 => new \Phplrt\Parser\Grammar\Alternation([43, 42, 1, 38, 52, 39, 51, 60]),
        66 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        67 => new \Phplrt\Parser\Grammar\Concatenation([66, 44]),
        68 => new \Phplrt\Parser\Grammar\Repetition(194, 0, INF),
        69 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        70 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        71 => new \Phplrt\Parser\Grammar\Optional(67),
        72 => new \Phplrt\Parser\Grammar\Optional(69),
        73 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 70, 28, 71, 68, 72]),
        74 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 68, 77]),
        75 => new \Phplrt\Parser\Grammar\Repetition(74, 0, INF),
        76 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        77 => new \Phplrt\Parser\Grammar\Optional(76),
        78 => new \Phplrt\Parser\Grammar\Repetition(80, 1, INF),
        79 => new \Phplrt\Parser\Grammar\Optional(78),
        80 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 81, 83, 28, 68, 84]),
        81 => new \Phplrt\Parser\Grammar\Optional(85),
        82 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        83 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        84 => new \Phplrt\Parser\Grammar\Optional(82),
        85 => new \Phplrt\Parser\Grammar\Concatenation([86, 87, 88]),
        86 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        87 => new \Phplrt\Parser\Grammar\Repetition(73, 0, INF),
        88 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        89 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        90 => new \Phplrt\Parser\Grammar\Concatenation([89, 44]),
        91 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        92 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        93 => new \Phplrt\Parser\Grammar\Optional(90),
        94 => new \Phplrt\Parser\Grammar\Optional(91),
        95 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 92, 28, 93, 68, 94]),
        96 => new \Phplrt\Parser\Grammar\Concatenation([98, 99]),
        97 => new \Phplrt\Parser\Grammar\Concatenation([2, 96]),
        98 => new \Phplrt\Parser\Grammar\Concatenation([100, 68]),
        99 => new \Phplrt\Parser\Grammar\Optional(101),
        100 => new \Phplrt\Parser\Grammar\Lexeme('T_SCHEMA', false),
        101 => new \Phplrt\Parser\Grammar\Concatenation([106, 107, 108]),
        102 => new \Phplrt\Parser\Grammar\Concatenation([2, 109, 110, 27, 68]),
        103 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        104 => new \Phplrt\Parser\Grammar\Optional(103),
        105 => new \Phplrt\Parser\Grammar\Concatenation([102, 104]),
        106 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        107 => new \Phplrt\Parser\Grammar\Repetition(105, 0, INF),
        108 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        109 => new \Phplrt\Parser\Grammar\Alternation([111, 112, 113]),
        110 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        111 => new \Phplrt\Parser\Grammar\Lexeme('T_QUERY', true),
        112 => new \Phplrt\Parser\Grammar\Lexeme('T_MUTATION', true),
        113 => new \Phplrt\Parser\Grammar\Lexeme('T_SUBSCRIPTION', true),
        114 => new \Phplrt\Parser\Grammar\Concatenation([119, 120, 4, 117, 118]),
        115 => new \Phplrt\Parser\Grammar\Concatenation([127, 126]),
        116 => new \Phplrt\Parser\Grammar\Concatenation([2, 114, 115]),
        117 => new \Phplrt\Parser\Grammar\Optional(121),
        118 => new \Phplrt\Parser\Grammar\Optional(125),
        119 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE', false),
        120 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        121 => new \Phplrt\Parser\Grammar\Concatenation([122, 123, 124]),
        122 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        123 => new \Phplrt\Parser\Grammar\Repetition(73, 0, INF),
        124 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        125 => new \Phplrt\Parser\Grammar\Lexeme('T_REPEATABLE', true),
        126 => new \Phplrt\Parser\Grammar\Concatenation([131, 3, 132]),
        127 => new \Phplrt\Parser\Grammar\Lexeme('T_ON', false),
        128 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        129 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        130 => new \Phplrt\Parser\Grammar\Concatenation([129, 3]),
        131 => new \Phplrt\Parser\Grammar\Optional(128),
        132 => new \Phplrt\Parser\Grammar\Repetition(130, 0, INF),
        133 => new \Phplrt\Parser\Grammar\Concatenation([135, 136]),
        134 => new \Phplrt\Parser\Grammar\Concatenation([2, 133]),
        135 => new \Phplrt\Parser\Grammar\Concatenation([137, 4, 68]),
        136 => new \Phplrt\Parser\Grammar\Optional(138),
        137 => new \Phplrt\Parser\Grammar\Lexeme('T_ENUM', false),
        138 => new \Phplrt\Parser\Grammar\Concatenation([139, 140, 141]),
        139 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        140 => new \Phplrt\Parser\Grammar\Repetition(74, 0, INF),
        141 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        142 => new \Phplrt\Parser\Grammar\Concatenation([144, 145]),
        143 => new \Phplrt\Parser\Grammar\Concatenation([2, 142]),
        144 => new \Phplrt\Parser\Grammar\Concatenation([146, 4, 68]),
        145 => new \Phplrt\Parser\Grammar\Optional(147),
        146 => new \Phplrt\Parser\Grammar\Lexeme('T_INPUT', false),
        147 => new \Phplrt\Parser\Grammar\Concatenation([148, 149, 150]),
        148 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        149 => new \Phplrt\Parser\Grammar\Repetition(95, 0, INF),
        150 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        151 => new \Phplrt\Parser\Grammar\Concatenation([153, 154]),
        152 => new \Phplrt\Parser\Grammar\Concatenation([2, 151]),
        153 => new \Phplrt\Parser\Grammar\Concatenation([156, 4, 155, 68]),
        154 => new \Phplrt\Parser\Grammar\Optional(157),
        155 => new \Phplrt\Parser\Grammar\Optional(162),
        156 => new \Phplrt\Parser\Grammar\Lexeme('T_INTERFACE', false),
        157 => new \Phplrt\Parser\Grammar\Concatenation([175, 79, 176]),
        158 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        159 => new \Phplrt\Parser\Grammar\Optional(78),
        160 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        161 => new \Phplrt\Parser\Grammar\Concatenation([158, 159, 160]),
        162 => new \Phplrt\Parser\Grammar\Concatenation([165, 166, 27, 167]),
        163 => new \Phplrt\Parser\Grammar\Alternation([168, 169]),
        164 => new \Phplrt\Parser\Grammar\Concatenation([163, 27]),
        165 => new \Phplrt\Parser\Grammar\Lexeme('T_IMPLEMENTS', false),
        166 => new \Phplrt\Parser\Grammar\Optional(163),
        167 => new \Phplrt\Parser\Grammar\Repetition(164, 0, INF),
        168 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        169 => new \Phplrt\Parser\Grammar\Lexeme('T_AND', false),
        170 => new \Phplrt\Parser\Grammar\Concatenation([172, 173]),
        171 => new \Phplrt\Parser\Grammar\Concatenation([2, 170]),
        172 => new \Phplrt\Parser\Grammar\Concatenation([174, 4, 155, 68]),
        173 => new \Phplrt\Parser\Grammar\Optional(157),
        174 => new \Phplrt\Parser\Grammar\Lexeme('T_TYPE', false),
        175 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        176 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        177 => new \Phplrt\Parser\Grammar\Concatenation([179]),
        178 => new \Phplrt\Parser\Grammar\Concatenation([2, 177]),
        179 => new \Phplrt\Parser\Grammar\Concatenation([180, 4, 68]),
        180 => new \Phplrt\Parser\Grammar\Lexeme('T_SCALAR', false),
        181 => new \Phplrt\Parser\Grammar\Concatenation([183, 184]),
        182 => new \Phplrt\Parser\Grammar\Concatenation([2, 181]),
        183 => new \Phplrt\Parser\Grammar\Concatenation([185, 4, 68]),
        184 => new \Phplrt\Parser\Grammar\Concatenation([188, 187]),
        185 => new \Phplrt\Parser\Grammar\Lexeme('T_UNION', false),
        186 => new \Phplrt\Parser\Grammar\Optional(184),
        187 => new \Phplrt\Parser\Grammar\Concatenation([192, 27, 193]),
        188 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        189 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        190 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        191 => new \Phplrt\Parser\Grammar\Concatenation([190, 27]),
        192 => new \Phplrt\Parser\Grammar\Optional(189),
        193 => new \Phplrt\Parser\Grammar\Repetition(191, 0, INF),
        194 => new \Phplrt\Parser\Grammar\Concatenation([196, 3, 197]),
        195 => new \Phplrt\Parser\Grammar\Concatenation([202, 203, 204]),
        196 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        197 => new \Phplrt\Parser\Grammar\Optional(195),
        198 => new \Phplrt\Parser\Grammar\Concatenation([3, 205, 44]),
        199 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        200 => new \Phplrt\Parser\Grammar\Optional(199),
        201 => new \Phplrt\Parser\Grammar\Concatenation([198, 200]),
        202 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        203 => new \Phplrt\Parser\Grammar\Repetition(201, 0, INF),
        204 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        205 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        206 => new \Phplrt\Parser\Grammar\Alternation([178, 171, 152, 182, 134, 143]),
        207 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        208 => new \Phplrt\Parser\Grammar\Concatenation([207, 96]),
        209 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        210 => new \Phplrt\Parser\Grammar\Concatenation([209, 133]),
        211 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        212 => new \Phplrt\Parser\Grammar\Concatenation([211, 142]),
        213 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        214 => new \Phplrt\Parser\Grammar\Concatenation([213, 151]),
        215 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        216 => new \Phplrt\Parser\Grammar\Concatenation([215, 170]),
        217 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        218 => new \Phplrt\Parser\Grammar\Concatenation([217, 177]),
        219 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        220 => new \Phplrt\Parser\Grammar\Concatenation([219, 181]),
        35 => new \Phplrt\Parser\Grammar\Alternation([208, 221]),
        221 => new \Phplrt\Parser\Grammar\Alternation([218, 216, 214, 220, 210, 212])
    ],
    'reducers' => [
        2 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\DescriptionNode($children === [] ? null : $children);
        },
        4 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\NameNode(
                $children[0]->value,
            );
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
        38 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\BoolLiteralNode::parse($children->getValue());
        },
        39 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ConstLiteralNode($children[0]);
        },
        42 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\FloatLiteralNode::parse($children->getValue());
        },
        43 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\IntLiteralNode::parse($children->getValue());
        },
        51 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ListLiteralNode($children);
        },
        52 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\NullLiteralNode();
        },
        60 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralNode($children);
        },
        53 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralFieldNode(
                $children[0],
                $children[1],
            );
        },
        62 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $this->stringPool[$children]
            ??= Expr\Literal\StringLiteralNode::parseMultilineString($children->getValue());
        },
        63 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $this->stringPool[$children]
            ??= Expr\Literal\StringLiteralNode::parseInlineString($children->getValue());
        },
        64 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\VariableNode($children[0]->getValue());
        },
        73 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\ArgumentNode(
                $children[1],
                $children[0],
                $children[2],
                $children[3] instanceof \ArrayObject ? null : $children[3],
                \end($children)->getArrayCopy(),
            );
        },
        75 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        74 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\EnumFieldNode(
                $children[1],
                $children[0],
                $children[2]->getArrayCopy(),
            );
        },
        79 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        80 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\FieldNode(
                $children[1],
                $children[0],
                $children[3],
                $children[2]->getArrayCopy(),
                $children[4]->getArrayCopy(),
            );
        },
        81 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        95 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\InputFieldNode(
                $children[1],
                $children[0],
                $children[2],
                $children[3] instanceof \ArrayObject ? null : $children[3],
                \end($children)->getArrayCopy(),
            );
        },
        97 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\SchemaDefinitionNode(
                $children[0],
                $children[2]->getArrayCopy(),
                $children[1]->getArrayCopy(),
            );
        },
        99 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        102 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\SchemaFieldNode(
                $children[1],
                $children[0],
                $children[2],
                $children[3]->getArrayCopy(),
            );
        },
        109 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\IdentifierNode($children->getValue());
        },
        116 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\DirectiveDefinitionNode(
                $children[1],
                $children[0],
                $children[2]->getArrayCopy(),
                $children[3]->getArrayCopy(),
                $children[4]->getArrayCopy(),
            );
        },
        117 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        118 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject(\array_filter([$children]));
        },
        125 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Stmt\Definition\DirectiveDefinition\Modifier::REPEATABLE;
        },
        126 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        134 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\EnumTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[3]->getArrayCopy(),
                $children[2]->getArrayCopy(),
            );
        },
        136 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        138 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        143 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InputObjectTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[3]->getArrayCopy(),
                $children[2]->getArrayCopy(),
            );
        },
        145 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        147 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        152 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InterfaceTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[2]->getArrayCopy(),
                $children[4]->getArrayCopy(),
                $children[3]->getArrayCopy(),
            );
        },
        154 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        155 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        171 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ObjectTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[2]->getArrayCopy(),
                $children[4]->getArrayCopy(),
                $children[3]->getArrayCopy(),
            );
        },
        173 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        178 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ScalarTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[2]->getArrayCopy(),
            );
        },
        182 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\UnionTypeDefinitionNode(
                $children[1],
                $children[0],
                $children[3]->getArrayCopy(),
                $children[2]->getArrayCopy(),
            );
        },
        186 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        184 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        68 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children ?? []);
        },
        194 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Execution\DirectiveNode(
                $children[0],
                isset($children[1]) ? $children[1]->getArrayCopy() : [],
            );
        },
        195 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        198 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Execution\ArgumentNode($children[0], $children[1]);
        },
        208 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\SchemaExtensionNode(
                $children[0]->getArrayCopy(),
                $children[1]->getArrayCopy(),
            );
        },
        210 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\EnumTypeExtensionNode(
                $children[0],
                $children[2]->getArrayCopy(),
                $children[1]->getArrayCopy(),
            );
        },
        212 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\InputObjectTypeExtensionNode(
                $children[0],
                $children[2]->getArrayCopy(),
                $children[1]->getArrayCopy(),
            );
        },
        214 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\InterfaceTypeExtensionNode(
                $children[0],
                $children[1]->getArrayCopy(),
                $children[3]->getArrayCopy(),
                $children[2]->getArrayCopy(),
            );
        },
        216 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\ObjectTypeExtensionNode(
                $children[0],
                $children[1]->getArrayCopy(),
                $children[3]->getArrayCopy(),
                $children[2]->getArrayCopy(),
            );
        },
        218 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\ScalarTypeExtensionNode(
                $children[0],
                $children[1]->getArrayCopy(),
            );
        },
        220 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Extension\UnionTypeExtensionNode(
                $children[0],
                $children[2]->getArrayCopy(),
                $children[1]->getArrayCopy(),
            );
        }
    ]
];
