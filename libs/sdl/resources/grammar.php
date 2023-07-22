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
        0 => new \Phplrt\Parser\Grammar\Repetition(62, 0, INF),
        1 => new \Phplrt\Parser\Grammar\Alternation([59, 60]),
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
        33 => new \Phplrt\Parser\Grammar\Lexeme('T_FALSE', true),
        34 => new \Phplrt\Parser\Grammar\Lexeme('T_TRUE', true),
        35 => new \Phplrt\Parser\Grammar\Alternation([33, 34]),
        36 => new \Phplrt\Parser\Grammar\Concatenation([3]),
        37 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT', true),
        38 => new \Phplrt\Parser\Grammar\Lexeme('T_FLOAT_EXP', true),
        39 => new \Phplrt\Parser\Grammar\Alternation([37, 38]),
        40 => new \Phplrt\Parser\Grammar\Lexeme('T_INT', true),
        41 => new \Phplrt\Parser\Grammar\Alternation([61, 40, 39, 1, 35, 49, 36, 48, 57]),
        42 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        43 => new \Phplrt\Parser\Grammar\Optional(42),
        44 => new \Phplrt\Parser\Grammar\Concatenation([41, 43]),
        45 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_OPEN', false),
        46 => new \Phplrt\Parser\Grammar\Repetition(44, 0, INF),
        47 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        48 => new \Phplrt\Parser\Grammar\Concatenation([45, 46, 47]),
        49 => new \Phplrt\Parser\Grammar\Lexeme('T_NULL', true),
        50 => new \Phplrt\Parser\Grammar\Concatenation([3, 58, 41]),
        51 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        52 => new \Phplrt\Parser\Grammar\Optional(51),
        53 => new \Phplrt\Parser\Grammar\Concatenation([50, 52]),
        54 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        55 => new \Phplrt\Parser\Grammar\Repetition(53, 0, INF),
        56 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        57 => new \Phplrt\Parser\Grammar\Concatenation([54, 55, 56]),
        58 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        59 => new \Phplrt\Parser\Grammar\Lexeme('T_BLOCK_STRING', true),
        60 => new \Phplrt\Parser\Grammar\Lexeme('T_STRING', true),
        61 => new \Phplrt\Parser\Grammar\Lexeme('T_VARIABLE', true),
        62 => new \Phplrt\Parser\Grammar\Alternation([63, 64]),
        63 => new \Phplrt\Parser\Grammar\Alternation([96, 115, 205]),
        65 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        66 => new \Phplrt\Parser\Grammar\Concatenation([65, 41]),
        67 => new \Phplrt\Parser\Grammar\Repetition(193, 0, INF),
        68 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        69 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        70 => new \Phplrt\Parser\Grammar\Optional(66),
        71 => new \Phplrt\Parser\Grammar\Optional(68),
        72 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 69, 28, 70, 67, 71]),
        73 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 67, 76]),
        74 => new \Phplrt\Parser\Grammar\Repetition(73, 0, INF),
        75 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        76 => new \Phplrt\Parser\Grammar\Optional(75),
        77 => new \Phplrt\Parser\Grammar\Repetition(79, 1, INF),
        78 => new \Phplrt\Parser\Grammar\Optional(77),
        79 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 80, 82, 28, 67, 83]),
        80 => new \Phplrt\Parser\Grammar\Optional(84),
        81 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        82 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        83 => new \Phplrt\Parser\Grammar\Optional(81),
        84 => new \Phplrt\Parser\Grammar\Concatenation([85, 86, 87]),
        85 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        86 => new \Phplrt\Parser\Grammar\Repetition(72, 0, INF),
        87 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        88 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        89 => new \Phplrt\Parser\Grammar\Concatenation([88, 41]),
        90 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        91 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        92 => new \Phplrt\Parser\Grammar\Optional(89),
        93 => new \Phplrt\Parser\Grammar\Optional(90),
        94 => new \Phplrt\Parser\Grammar\Concatenation([2, 3, 91, 28, 92, 67, 93]),
        95 => new \Phplrt\Parser\Grammar\Concatenation([97, 98]),
        96 => new \Phplrt\Parser\Grammar\Concatenation([2, 95]),
        97 => new \Phplrt\Parser\Grammar\Concatenation([99, 67]),
        98 => new \Phplrt\Parser\Grammar\Optional(100),
        99 => new \Phplrt\Parser\Grammar\Lexeme('T_SCHEMA', false),
        100 => new \Phplrt\Parser\Grammar\Concatenation([105, 106, 107]),
        101 => new \Phplrt\Parser\Grammar\Concatenation([2, 108, 109, 27, 67]),
        102 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        103 => new \Phplrt\Parser\Grammar\Optional(102),
        104 => new \Phplrt\Parser\Grammar\Concatenation([101, 103]),
        105 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        106 => new \Phplrt\Parser\Grammar\Repetition(104, 0, INF),
        107 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        108 => new \Phplrt\Parser\Grammar\Alternation([110, 111, 112]),
        109 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        110 => new \Phplrt\Parser\Grammar\Lexeme('T_QUERY', true),
        111 => new \Phplrt\Parser\Grammar\Lexeme('T_MUTATION', true),
        112 => new \Phplrt\Parser\Grammar\Lexeme('T_SUBSCRIPTION', true),
        113 => new \Phplrt\Parser\Grammar\Concatenation([118, 119, 3, 116, 117]),
        114 => new \Phplrt\Parser\Grammar\Concatenation([126, 125]),
        115 => new \Phplrt\Parser\Grammar\Concatenation([2, 113, 114]),
        116 => new \Phplrt\Parser\Grammar\Optional(120),
        117 => new \Phplrt\Parser\Grammar\Optional(124),
        118 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE', false),
        119 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        120 => new \Phplrt\Parser\Grammar\Concatenation([121, 122, 123]),
        121 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        122 => new \Phplrt\Parser\Grammar\Repetition(72, 0, INF),
        123 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        124 => new \Phplrt\Parser\Grammar\Lexeme('T_REPEATABLE', true),
        125 => new \Phplrt\Parser\Grammar\Concatenation([130, 3, 131]),
        126 => new \Phplrt\Parser\Grammar\Lexeme('T_ON', false),
        127 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        128 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        129 => new \Phplrt\Parser\Grammar\Concatenation([128, 3]),
        130 => new \Phplrt\Parser\Grammar\Optional(127),
        131 => new \Phplrt\Parser\Grammar\Repetition(129, 0, INF),
        132 => new \Phplrt\Parser\Grammar\Concatenation([134, 135]),
        133 => new \Phplrt\Parser\Grammar\Concatenation([2, 132]),
        134 => new \Phplrt\Parser\Grammar\Concatenation([136, 3, 67]),
        135 => new \Phplrt\Parser\Grammar\Optional(137),
        136 => new \Phplrt\Parser\Grammar\Lexeme('T_ENUM', false),
        137 => new \Phplrt\Parser\Grammar\Concatenation([138, 139, 140]),
        138 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        139 => new \Phplrt\Parser\Grammar\Repetition(73, 0, INF),
        140 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        141 => new \Phplrt\Parser\Grammar\Concatenation([143, 144]),
        142 => new \Phplrt\Parser\Grammar\Concatenation([2, 141]),
        143 => new \Phplrt\Parser\Grammar\Concatenation([145, 3, 67]),
        144 => new \Phplrt\Parser\Grammar\Optional(146),
        145 => new \Phplrt\Parser\Grammar\Lexeme('T_INPUT', false),
        146 => new \Phplrt\Parser\Grammar\Concatenation([147, 148, 149]),
        147 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        148 => new \Phplrt\Parser\Grammar\Repetition(94, 0, INF),
        149 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        150 => new \Phplrt\Parser\Grammar\Concatenation([152, 153]),
        151 => new \Phplrt\Parser\Grammar\Concatenation([2, 150]),
        152 => new \Phplrt\Parser\Grammar\Concatenation([155, 3, 154, 67]),
        153 => new \Phplrt\Parser\Grammar\Optional(156),
        154 => new \Phplrt\Parser\Grammar\Optional(161),
        155 => new \Phplrt\Parser\Grammar\Lexeme('T_INTERFACE', false),
        156 => new \Phplrt\Parser\Grammar\Concatenation([174, 78, 175]),
        157 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        158 => new \Phplrt\Parser\Grammar\Optional(77),
        159 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        160 => new \Phplrt\Parser\Grammar\Concatenation([157, 158, 159]),
        161 => new \Phplrt\Parser\Grammar\Concatenation([164, 165, 27, 166]),
        162 => new \Phplrt\Parser\Grammar\Alternation([167, 168]),
        163 => new \Phplrt\Parser\Grammar\Concatenation([162, 27]),
        164 => new \Phplrt\Parser\Grammar\Lexeme('T_IMPLEMENTS', false),
        165 => new \Phplrt\Parser\Grammar\Optional(162),
        166 => new \Phplrt\Parser\Grammar\Repetition(163, 0, INF),
        167 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        168 => new \Phplrt\Parser\Grammar\Lexeme('T_AND', false),
        169 => new \Phplrt\Parser\Grammar\Concatenation([171, 172]),
        170 => new \Phplrt\Parser\Grammar\Concatenation([2, 169]),
        171 => new \Phplrt\Parser\Grammar\Concatenation([173, 3, 154, 67]),
        172 => new \Phplrt\Parser\Grammar\Optional(156),
        173 => new \Phplrt\Parser\Grammar\Lexeme('T_TYPE', false),
        174 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_OPEN', false),
        175 => new \Phplrt\Parser\Grammar\Lexeme('T_BRACE_CLOSE', false),
        176 => new \Phplrt\Parser\Grammar\Concatenation([178]),
        177 => new \Phplrt\Parser\Grammar\Concatenation([2, 176]),
        178 => new \Phplrt\Parser\Grammar\Concatenation([179, 3, 67]),
        179 => new \Phplrt\Parser\Grammar\Lexeme('T_SCALAR', false),
        180 => new \Phplrt\Parser\Grammar\Concatenation([182, 183]),
        181 => new \Phplrt\Parser\Grammar\Concatenation([2, 180]),
        182 => new \Phplrt\Parser\Grammar\Concatenation([184, 3, 67]),
        183 => new \Phplrt\Parser\Grammar\Concatenation([187, 186]),
        184 => new \Phplrt\Parser\Grammar\Lexeme('T_UNION', false),
        185 => new \Phplrt\Parser\Grammar\Optional(183),
        186 => new \Phplrt\Parser\Grammar\Concatenation([191, 27, 192]),
        187 => new \Phplrt\Parser\Grammar\Lexeme('T_EQUAL', false),
        188 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        189 => new \Phplrt\Parser\Grammar\Lexeme('T_OR', false),
        190 => new \Phplrt\Parser\Grammar\Concatenation([189, 27]),
        191 => new \Phplrt\Parser\Grammar\Optional(188),
        192 => new \Phplrt\Parser\Grammar\Repetition(190, 0, INF),
        193 => new \Phplrt\Parser\Grammar\Concatenation([195, 3, 196]),
        194 => new \Phplrt\Parser\Grammar\Concatenation([201, 202, 203]),
        195 => new \Phplrt\Parser\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        196 => new \Phplrt\Parser\Grammar\Optional(194),
        197 => new \Phplrt\Parser\Grammar\Concatenation([3, 204, 41]),
        198 => new \Phplrt\Parser\Grammar\Lexeme('T_COMMA', false),
        199 => new \Phplrt\Parser\Grammar\Optional(198),
        200 => new \Phplrt\Parser\Grammar\Concatenation([197, 199]),
        201 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        202 => new \Phplrt\Parser\Grammar\Repetition(200, 0, INF),
        203 => new \Phplrt\Parser\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        204 => new \Phplrt\Parser\Grammar\Lexeme('T_COLON', false),
        205 => new \Phplrt\Parser\Grammar\Alternation([177, 170, 151, 181, 133, 142]),
        206 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        207 => new \Phplrt\Parser\Grammar\Concatenation([206, 95]),
        208 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        209 => new \Phplrt\Parser\Grammar\Concatenation([208, 132]),
        210 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        211 => new \Phplrt\Parser\Grammar\Concatenation([210, 141]),
        212 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        213 => new \Phplrt\Parser\Grammar\Concatenation([212, 150]),
        214 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        215 => new \Phplrt\Parser\Grammar\Concatenation([214, 169]),
        216 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        217 => new \Phplrt\Parser\Grammar\Concatenation([216, 176]),
        218 => new \Phplrt\Parser\Grammar\Lexeme('T_EXTEND', false),
        219 => new \Phplrt\Parser\Grammar\Concatenation([218, 180]),
        64 => new \Phplrt\Parser\Grammar\Alternation([207, 220]),
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
        35 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\BoolLiteralNode::parse($children->getValue());
        },
        36 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ConstLiteralNode($children[0]);
        },
        39 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\FloatLiteralNode::parse($children->getValue());
        },
        40 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\IntLiteralNode::parse($children->getValue());
        },
        48 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ListLiteralNode($children);
        },
        49 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\NullLiteralNode();
        },
        57 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralNode($children);
        },
        50 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\ObjectLiteralFieldNode(
            $children[0],
            $children[1],
        );
        },
        59 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\StringLiteralNode::parse(\substr($children->getValue(), 3, -3));
        },
        60 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Expr\Literal\StringLiteralNode::parse(\substr($children->getValue(), 1, -1));
        },
        61 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Expr\Literal\VariableLiteralNode($children[0]->getValue());
        },
        72 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\ArgumentNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3] instanceof \ArrayObject ? null : $children[3],
            \end($children)->getArrayCopy(),
        );
        },
        74 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        73 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\EnumFieldNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
        );
        },
        78 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        79 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\FieldNode(
            $children[1],
            $children[0],
            $children[3],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
        );
        },
        80 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        94 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\InputFieldNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3] instanceof \ArrayObject ? null : $children[3],
            \end($children)->getArrayCopy(),
        );
        },
        96 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\SchemaDefinitionNode(
            $children[0],
            $children[2]->getArrayCopy(),
            $children[1]->getArrayCopy(),
        );
        },
        98 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        101 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\SchemaFieldNode(
            $children[1],
            $children[0],
            $children[2],
            $children[3]->getArrayCopy(),
        );
        },
        108 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Node\IdentifierNode($children->getValue());
        },
        115 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\DirectiveDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[3]->getArrayCopy(),
            $children[4]->getArrayCopy(),
        );
        },
        116 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        117 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject(\array_filter([$children]));
        },
        124 => function (\Phplrt\Parser\Context $ctx, $children) {
            return Stmt\Definition\DirectiveDefinition\Modifier::REPEATABLE;
        },
        125 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        133 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\EnumTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        135 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        137 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        142 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InputObjectTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        144 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        146 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        151 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\InterfaceTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
            $children[3]->getArrayCopy(),
        );
        },
        153 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        154 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        170 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ObjectTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
            $children[4]->getArrayCopy(),
            $children[3]->getArrayCopy(),
        );
        },
        172 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        177 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\ScalarTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[2]->getArrayCopy(),
        );
        },
        181 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Definition\UnionTypeDefinitionNode(
            $children[1],
            $children[0],
            $children[3]->getArrayCopy(),
            $children[2]->getArrayCopy(),
        );
        },
        185 => function (\Phplrt\Parser\Context $ctx, $children) {
            return $children === [] ? new \ArrayObject() : $children;
        },
        183 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        67 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children ?? []);
        },
        193 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Execution\DirectiveNode(
            $children[0],
            isset($children[1]) ? $children[1]->getArrayCopy() : [],
        );
        },
        194 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new \ArrayObject($children);
        },
        197 => function (\Phplrt\Parser\Context $ctx, $children) {
            return new Stmt\Execution\ArgumentNode($children[0], $children[1]);
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