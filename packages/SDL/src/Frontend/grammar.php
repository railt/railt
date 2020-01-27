<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @noinspection ALL
 */

declare(strict_types=1);

use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Grammar\RuleInterface;
use Railt\SDL\Frontend\Ast;
use Railt\TypeSystem\Value;

return [

    /**
     * -------------------------------------------------------------------------
     *  Initial State
     * -------------------------------------------------------------------------
     *
     * The initial state (initial rule identifier) of the parser.
     *
     */
    'initial' => 0,
    
    /**
     * -------------------------------------------------------------------------
     *  Lexer Tokens
     * -------------------------------------------------------------------------
     *
     * A GraphQL document is comprised of several kinds of indivisible
     * lexical tokens defined here in a lexical grammar by patterns
     * of source Unicode characters.
     *
     * Tokens are later used as terminal symbols in a GraphQL Document
     * syntactic grammars.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Lexical-Tokens
     * @var string[]
     *
     */
    'lexemes' => [
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
        'T_BOM' => '\\x{FEFF}',
        'T_HTAB' => '\\x09+',
        'T_WHITESPACE' => '\\x20+',
        'T_LF' => '\\x0A+',
        'T_CR' => '\\x0D+',
        'T_INVISIBLE_WHITESPACES' => '(?:\\x{000B}|\\x{000C}|\\x{0085}|\\x{00A0}|\\x{1680}|[\\x{2000}-\\x{200A}]|\\x{2028}|\\x{2029}|\\x{202F}|\\x{205F}|\\x{3000})+',
        'T_INVISIBLE' => '(?:\\x{180E}|\\x{200B}|\\x{200C}|\\x{200D}|\\x{2060})+',
    ],
     
    /**
     * -------------------------------------------------------------------------
     *  Lexer Ignored Tokens
     * -------------------------------------------------------------------------
     *
     * Before and after every lexical token may be any amount of ignored tokens
     * including WhiteSpace and Comment. No ignored regions of a source document
     * are significant, however otherwise ignored source characters may appear
     * within a lexical token in a significant way, for example a StringValue
     * may contain white space characters and commas.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Ignored-Tokens
     * @var string[]
     *
     */
    'skips' => [
        'T_COMMENT',
        'T_BOM',
        'T_HTAB',
        'T_WHITESPACE',
        'T_LF',
        'T_CR',
        'T_INVISIBLE_WHITESPACES',
        'T_INVISIBLE',
    ],
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Grammar
     * -------------------------------------------------------------------------
     *
     * Array of transition rules for the parser.
     *
     */
    'grammar' => [
        1 => new \Phplrt\Grammar\Alternation([
            49,
            50,
        ]),
        2 => new \Phplrt\Grammar\Optional(1),
        3 => new \Phplrt\Grammar\Lexeme('T_TRUE', true),
        4 => new \Phplrt\Grammar\Lexeme('T_FALSE', true),
        5 => new \Phplrt\Grammar\Lexeme('T_NULL', true),
        6 => new \Phplrt\Grammar\Lexeme('T_TYPE', true),
        7 => new \Phplrt\Grammar\Lexeme('T_ENUM', true),
        8 => new \Phplrt\Grammar\Lexeme('T_UNION', true),
        9 => new \Phplrt\Grammar\Lexeme('T_INTERFACE', true),
        10 => new \Phplrt\Grammar\Lexeme('T_SCHEMA', true),
        11 => new \Phplrt\Grammar\Lexeme('T_SCALAR', true),
        12 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE', true),
        13 => new \Phplrt\Grammar\Lexeme('T_INPUT', true),
        14 => new \Phplrt\Grammar\Lexeme('T_EXTEND', true),
        15 => new \Phplrt\Grammar\Lexeme('T_EXTENDS', true),
        16 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', true),
        17 => new \Phplrt\Grammar\Lexeme('T_ON', true),
        18 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        19 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        20 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        21 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        22 => new \Phplrt\Grammar\Lexeme('T_NAME', true),
        23 => new \Phplrt\Grammar\Alternation([
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
        ]),
        24 => new \Phplrt\Grammar\Lexeme('T_FALSE', true),
        25 => new \Phplrt\Grammar\Lexeme('T_TRUE', true),
        26 => new \Phplrt\Grammar\Alternation([
            24,
            25,
        ]),
        27 => new \Phplrt\Grammar\Concatenation([
            23,
        ]),
        28 => new \Phplrt\Grammar\Lexeme('T_FLOAT', true),
        29 => new \Phplrt\Grammar\Lexeme('T_FLOAT_EXP', true),
        30 => new \Phplrt\Grammar\Alternation([
            28,
            29,
        ]),
        31 => new \Phplrt\Grammar\Lexeme('T_INT', true),
        32 => new \Phplrt\Grammar\Alternation([
            51,
            52,
            39,
            48,
        ]),
        33 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        34 => new \Phplrt\Grammar\Optional(33),
        35 => new \Phplrt\Grammar\Concatenation([
            32,
            34,
        ]),
        36 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        37 => new \Phplrt\Grammar\Repetition(35, 0, INF),
        38 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        39 => new \Phplrt\Grammar\Concatenation([
            36,
            37,
            38,
        ]),
        40 => new \Phplrt\Grammar\Lexeme('T_NULL', true),
        41 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        42 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        43 => new \Phplrt\Grammar\Optional(41),
        44 => new \Phplrt\Grammar\Concatenation([
            23,
            42,
            32,
            43,
        ]),
        45 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        46 => new \Phplrt\Grammar\Repetition(44, 0, INF),
        47 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        48 => new \Phplrt\Grammar\Concatenation([
            45,
            46,
            47,
        ]),
        49 => new \Phplrt\Grammar\Lexeme('T_BLOCK_STRING', true),
        50 => new \Phplrt\Grammar\Lexeme('T_STRING', true),
        51 => new \Phplrt\Grammar\Lexeme('T_VARIABLE', true),
        52 => new \Phplrt\Grammar\Alternation([
            31,
            30,
            1,
            26,
            40,
            27,
        ]),
        53 => new \Phplrt\Grammar\Concatenation([
            59,
            60,
        ]),
        54 => new \Phplrt\Grammar\Concatenation([
            57,
            56,
            58,
        ]),
        55 => new \Phplrt\Grammar\Concatenation([
            23,
        ]),
        56 => new \Phplrt\Grammar\Alternation([
            53,
            54,
            55,
        ]),
        57 => new \Phplrt\Grammar\Lexeme('T_BRACKET_OPEN', false),
        58 => new \Phplrt\Grammar\Lexeme('T_BRACKET_CLOSE', false),
        59 => new \Phplrt\Grammar\Alternation([
            54,
            55,
        ]),
        60 => new \Phplrt\Grammar\Lexeme('T_NON_NULL', false),
        61 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        62 => new \Phplrt\Grammar\Concatenation([
            61,
            23,
        ]),
        63 => new \Phplrt\Grammar\Concatenation([
            87,
            89,
        ]),
        64 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        65 => new \Phplrt\Grammar\Concatenation([
            64,
            63,
        ]),
        66 => new \Phplrt\Grammar\Concatenation([
            158,
            160,
        ]),
        67 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        68 => new \Phplrt\Grammar\Concatenation([
            67,
            66,
        ]),
        69 => new \Phplrt\Grammar\Concatenation([
            167,
            169,
        ]),
        70 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        71 => new \Phplrt\Grammar\Concatenation([
            70,
            69,
        ]),
        72 => new \Phplrt\Grammar\Concatenation([
            177,
            179,
        ]),
        73 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        74 => new \Phplrt\Grammar\Concatenation([
            73,
            72,
        ]),
        75 => new \Phplrt\Grammar\Concatenation([
            195,
            197,
        ]),
        76 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        77 => new \Phplrt\Grammar\Concatenation([
            76,
            75,
        ]),
        78 => new \Phplrt\Grammar\Concatenation([
            205,
        ]),
        79 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        80 => new \Phplrt\Grammar\Concatenation([
            79,
            78,
        ]),
        81 => new \Phplrt\Grammar\Concatenation([
            209,
            211,
        ]),
        82 => new \Phplrt\Grammar\Lexeme('T_EXTEND', false),
        83 => new \Phplrt\Grammar\Concatenation([
            82,
            81,
        ]),
        84 => new \Phplrt\Grammar\Alternation([
            80,
            77,
            74,
            83,
            68,
            71,
        ]),
        85 => new \Phplrt\Grammar\Alternation([
            65,
            84,
        ]),
        86 => new \Phplrt\Grammar\Concatenation([
            2,
            63,
        ]),
        87 => new \Phplrt\Grammar\Concatenation([
            91,
            92,
        ]),
        88 => new \Phplrt\Grammar\Concatenation([
            97,
            98,
            99,
        ]),
        89 => new \Phplrt\Grammar\Optional(88),
        90 => new \Phplrt\Grammar\Concatenation([
            62,
            225,
        ]),
        91 => new \Phplrt\Grammar\Lexeme('T_SCHEMA', false),
        92 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        93 => new \Phplrt\Grammar\Concatenation([
            100,
            101,
            55,
        ]),
        94 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        95 => new \Phplrt\Grammar\Optional(94),
        96 => new \Phplrt\Grammar\Concatenation([
            93,
            95,
        ]),
        97 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        98 => new \Phplrt\Grammar\Repetition(96, 0, INF),
        99 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        100 => new \Phplrt\Grammar\Alternation([
            102,
            103,
            104,
        ]),
        101 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        102 => new \Phplrt\Grammar\Lexeme('T_QUERY', true),
        103 => new \Phplrt\Grammar\Lexeme('T_MUTATION', true),
        104 => new \Phplrt\Grammar\Lexeme('T_SUBSCRIPTION', true),
        105 => new \Phplrt\Grammar\Concatenation([
            110,
            111,
            23,
            112,
            113,
        ]),
        106 => new \Phplrt\Grammar\Concatenation([
            119,
            118,
        ]),
        107 => new \Phplrt\Grammar\Concatenation([
            2,
            105,
            106,
        ]),
        108 => new \Phplrt\Grammar\Concatenation([
            115,
            116,
            117,
        ]),
        109 => new \Phplrt\Grammar\Lexeme('T_REPEATABLE', true),
        110 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE', false),
        111 => new \Phplrt\Grammar\Lexeme('T_DIRECTIVE_AT', false),
        112 => new \Phplrt\Grammar\Optional(108),
        113 => new \Phplrt\Grammar\Optional(109),
        114 => new \Phplrt\Grammar\Concatenation([
            2,
            125,
            128,
            129,
            130,
        ]),
        115 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        116 => new \Phplrt\Grammar\Repetition(114, 0, INF),
        117 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        118 => new \Phplrt\Grammar\Concatenation([
            123,
            23,
            124,
        ]),
        119 => new \Phplrt\Grammar\Lexeme('T_ON', false),
        120 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        121 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        122 => new \Phplrt\Grammar\Concatenation([
            121,
            23,
        ]),
        123 => new \Phplrt\Grammar\Optional(120),
        124 => new \Phplrt\Grammar\Repetition(122, 0, INF),
        125 => new \Phplrt\Grammar\Concatenation([
            23,
            131,
            56,
        ]),
        126 => new \Phplrt\Grammar\Concatenation([
            132,
            32,
        ]),
        127 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        128 => new \Phplrt\Grammar\Optional(126),
        129 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        130 => new \Phplrt\Grammar\Optional(127),
        131 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        132 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        133 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        134 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        135 => new \Phplrt\Grammar\Optional(133),
        136 => new \Phplrt\Grammar\Concatenation([
            2,
            23,
            134,
            135,
        ]),
        137 => new \Phplrt\Grammar\Concatenation([
            2,
            23,
            141,
            142,
            56,
            143,
            144,
        ]),
        138 => new \Phplrt\Grammar\Repetition(137, 1, INF),
        139 => new \Phplrt\Grammar\Concatenation([
            145,
            146,
            147,
        ]),
        140 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        141 => new \Phplrt\Grammar\Optional(139),
        142 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        143 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        144 => new \Phplrt\Grammar\Optional(140),
        145 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        146 => new \Phplrt\Grammar\Repetition(114, 0, INF),
        147 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        148 => new \Phplrt\Grammar\Concatenation([
            23,
            155,
            56,
        ]),
        149 => new \Phplrt\Grammar\Concatenation([
            156,
            32,
        ]),
        150 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        151 => new \Phplrt\Grammar\Optional(149),
        152 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        153 => new \Phplrt\Grammar\Optional(150),
        154 => new \Phplrt\Grammar\Concatenation([
            2,
            148,
            151,
            152,
            153,
        ]),
        155 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        156 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        157 => new \Phplrt\Grammar\Concatenation([
            2,
            66,
        ]),
        158 => new \Phplrt\Grammar\Concatenation([
            161,
            23,
            162,
        ]),
        159 => new \Phplrt\Grammar\Concatenation([
            163,
            164,
            165,
        ]),
        160 => new \Phplrt\Grammar\Optional(159),
        161 => new \Phplrt\Grammar\Lexeme('T_ENUM', false),
        162 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        163 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        164 => new \Phplrt\Grammar\Repetition(136, 0, INF),
        165 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        166 => new \Phplrt\Grammar\Concatenation([
            2,
            69,
        ]),
        167 => new \Phplrt\Grammar\Concatenation([
            170,
            23,
            171,
        ]),
        168 => new \Phplrt\Grammar\Concatenation([
            172,
            173,
            174,
        ]),
        169 => new \Phplrt\Grammar\Optional(168),
        170 => new \Phplrt\Grammar\Lexeme('T_INPUT', false),
        171 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        172 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        173 => new \Phplrt\Grammar\Repetition(154, 0, INF),
        174 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        175 => new \Phplrt\Grammar\Optional(2),
        176 => new \Phplrt\Grammar\Concatenation([
            175,
            72,
        ]),
        177 => new \Phplrt\Grammar\Concatenation([
            181,
            23,
            182,
            183,
        ]),
        178 => new \Phplrt\Grammar\Concatenation([
            184,
            185,
            186,
        ]),
        179 => new \Phplrt\Grammar\Optional(178),
        180 => new \Phplrt\Grammar\Concatenation([
            189,
            190,
            55,
            191,
        ]),
        181 => new \Phplrt\Grammar\Lexeme('T_INTERFACE', false),
        182 => new \Phplrt\Grammar\Optional(180),
        183 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        184 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        185 => new \Phplrt\Grammar\Optional(138),
        186 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        187 => new \Phplrt\Grammar\Alternation([
            192,
            193,
        ]),
        188 => new \Phplrt\Grammar\Concatenation([
            187,
            55,
        ]),
        189 => new \Phplrt\Grammar\Lexeme('T_IMPLEMENTS', false),
        190 => new \Phplrt\Grammar\Optional(187),
        191 => new \Phplrt\Grammar\Repetition(188, 0, INF),
        192 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        193 => new \Phplrt\Grammar\Lexeme('T_AND', false),
        194 => new \Phplrt\Grammar\Concatenation([
            2,
            75,
        ]),
        195 => new \Phplrt\Grammar\Concatenation([
            198,
            23,
            199,
            200,
        ]),
        196 => new \Phplrt\Grammar\Concatenation([
            201,
            202,
            203,
        ]),
        197 => new \Phplrt\Grammar\Optional(196),
        198 => new \Phplrt\Grammar\Lexeme('T_TYPE', false),
        199 => new \Phplrt\Grammar\Optional(180),
        200 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        201 => new \Phplrt\Grammar\Lexeme('T_BRACE_OPEN', false),
        202 => new \Phplrt\Grammar\Optional(138),
        203 => new \Phplrt\Grammar\Lexeme('T_BRACE_CLOSE', false),
        204 => new \Phplrt\Grammar\Concatenation([
            2,
            78,
        ]),
        205 => new \Phplrt\Grammar\Concatenation([
            206,
            23,
            207,
        ]),
        206 => new \Phplrt\Grammar\Lexeme('T_SCALAR', false),
        207 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        208 => new \Phplrt\Grammar\Concatenation([
            2,
            81,
        ]),
        209 => new \Phplrt\Grammar\Concatenation([
            212,
            23,
            213,
        ]),
        210 => new \Phplrt\Grammar\Concatenation([
            215,
            216,
        ]),
        211 => new \Phplrt\Grammar\Optional(210),
        212 => new \Phplrt\Grammar\Lexeme('T_UNION', false),
        213 => new \Phplrt\Grammar\Repetition(90, 0, INF),
        214 => new \Phplrt\Grammar\Concatenation([
            220,
            55,
            221,
        ]),
        215 => new \Phplrt\Grammar\Lexeme('T_EQUAL', false),
        216 => new \Phplrt\Grammar\Optional(214),
        217 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        218 => new \Phplrt\Grammar\Lexeme('T_OR', false),
        219 => new \Phplrt\Grammar\Concatenation([
            218,
            55,
        ]),
        220 => new \Phplrt\Grammar\Optional(217),
        221 => new \Phplrt\Grammar\Repetition(219, 0, INF),
        222 => new \Phplrt\Grammar\Alternation([
            204,
            194,
            176,
            208,
            157,
            166,
        ]),
        223 => new \Phplrt\Grammar\Alternation([
            86,
            107,
            222,
        ]),
        224 => new \Phplrt\Grammar\Concatenation([
            230,
            231,
            232,
        ]),
        225 => new \Phplrt\Grammar\Optional(224),
        227 => new \Phplrt\Grammar\Lexeme('T_COMMA', false),
        228 => new \Phplrt\Grammar\Optional(227),
        229 => new \Phplrt\Grammar\Concatenation([
            226,
            228,
        ]),
        230 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_OPEN', false),
        231 => new \Phplrt\Grammar\Repetition(229, 0, INF),
        232 => new \Phplrt\Grammar\Lexeme('T_PARENTHESIS_CLOSE', false),
        233 => new \Phplrt\Grammar\Lexeme('T_COLON', false),
        226 => new \Phplrt\Grammar\Concatenation([
            23,
            233,
            32,
        ]),
        0 => new \Phplrt\Grammar\Repetition(234, 0, INF),
        234 => new \Phplrt\Grammar\Alternation([
            223,
            85,
        ])
    ],
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Reducers
     * -------------------------------------------------------------------------
     *
     * Array of abstract syntax tree reducers.
     *
     */
    'reducers' => [
        2 => static function ($children) {
            return Ast\Description::create($children ?: null);
        },
        23 => static function ($children) {
            return Ast\Identifier::create($children);
        },
        26 => static function ($children) {
            return Value\BooleanValue::parse($children->getName() === 'T_TRUE');
        },
        27 => static function ($children) {
            return Value\EnumValue::parse($children[0]->value);
        },
        30 => static function ($children) {
            return Value\FloatValue::parse($children->getValue());
        },
        31 => static function ($children) {
            return Value\IntValue::parse($children->getValue());
        },
        39 => static function ($children) {
            return Value\ListValue::parse($children);
        },
        40 => static function ($children) {
            return Value\NullValue::parse(null);
        },
        48 => static function ($children) {
            $result = [];

            for ($i = 0, $count = \count((array)$children); $i < $count; $i += 2) {
                $result[$children[$i]->value] = $children[$i + 1];
            }

            return Value\InputObjectValue::parse($result);
        },
        49 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 3, -3));
        },
        50 => static function ($children) {
            return Value\StringValue::parse(\substr($children->getValue(), 1, -1));
        },
        51 => static function ($children) {
            return Ast\Value\VariableValueNode::parse($children[0]->getValue());
        },
        54 => static function ($children) {
            return Ast\Type\ListTypeNode::create($children);
        },
        53 => static function ($children) {
            return Ast\Type\NonNullTypeNode::create($children);
        },
        55 => static function ($children) {
            return Ast\Type\NamedTypeNode::create($children);
        },
        62 => static function ($children) {
            return Ast\Type\NamedDirectiveNode::create($children);
        },
        65 => static function ($children) {
            return Ast\Extension\SchemaExtensionNode::create($children);
        },
        68 => static function ($children) {
            return Ast\Extension\Type\EnumTypeExtensionNode::create($children);
        },
        71 => static function ($children) {
            return Ast\Extension\Type\InputObjectTypeExtensionNode::create($children);
        },
        74 => static function ($children) {
            return Ast\Extension\Type\InterfaceTypeExtensionNode::create($children);
        },
        77 => static function ($children) {
            return Ast\Extension\Type\ObjectTypeExtensionNode::create($children);
        },
        80 => static function ($children) {
            return Ast\Extension\Type\ScalarTypeExtensionNode::create($children);
        },
        83 => static function ($children) {
            return Ast\Extension\Type\UnionTypeExtensionNode::create($children);
        },
        86 => static function ($children) {
            return Ast\Definition\SchemaDefinitionNode::create($children);
        },
        93 => static function ($children) {
            return Ast\Definition\OperationTypeDefinitionNode::create($children);
        },
        107 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionNode::create($children);
        },
        109 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionIsRepeatableNode::create();
        },
        118 => static function ($children) {
            return Ast\Definition\DirectiveDefinitionLocationNode::create($children);
        },
        114 => static function ($children) {
            return Ast\Definition\ArgumentDefinitionNode::create($children);
        },
        136 => static function ($children) {
            return Ast\Definition\EnumValueDefinitionNode::create($children);
        },
        137 => static function ($children) {
            return Ast\Definition\FieldDefinitionNode::create($children);
        },
        154 => static function ($children) {
            return Ast\Definition\InputFieldDefinitionNode::create($children);
        },
        157 => static function ($children) {
            return Ast\Definition\Type\EnumTypeDefinitionNode::create($children);
        },
        166 => static function ($children) {
            return Ast\Definition\Type\InputObjectTypeDefinitionNode::create($children);
        },
        176 => static function ($children) {
            return Ast\Definition\Type\InterfaceTypeDefinitionNode::create($children);
        },
        180 => static function ($children) {
            return Ast\Definition\Type\ImplementedInterfaceNode::create($children);
        },
        194 => static function ($children) {
            return Ast\Definition\Type\ObjectTypeDefinitionNode::create($children);
        },
        204 => static function ($children) {
            return Ast\Definition\Type\ScalarTypeDefinitionNode::create($children);
        },
        208 => static function ($children) {
            return Ast\Definition\Type\UnionTypeDefinitionNode::create($children);
        },
        214 => static function ($children) {
            return Ast\Definition\Type\UnionMemberNode::create($children);
        },
        90 => static function ($children) {
            return Ast\Executable\DirectiveNode::create($children);
        },
        226 => static function ($children) {
            return Ast\Executable\ArgumentNode::create($children);
        }
    ],

];