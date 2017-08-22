<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Hoa\File\Read;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Lexer;
use Hoa\Compiler\Llk\Parser;


foreach ([
    // Package installation path
    __DIR__ . '/../../../autoload.php',
    // Root installation path
    __DIR__ . '/../vendor/autoload.php',
] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

function print_s(Parser $compiler, $data)
{
    $lexer = new Lexer();
    $sequence = $lexer->lexMe($data, $compiler->getTokens());

    $mask = "%4s %-20s %-25s %-50s %7s\n";

    $header = sprintf($mask, '#', 'NAMESPACE', 'TOKEN', 'VALUE', 'OFFSET');

    echo str_repeat('-', strlen($header)) . "\n" .
        $header .
    str_repeat('-', strlen($header)) . "\n";

    foreach ($sequence as $i => $token) {
        $value = str_replace("\n", '\n', $token['value']);
        printf($mask, $i, $token['namespace'], $token['token'], $value, $token['offset']);
    }

    echo str_repeat('-', strlen($header)) . "\n";
}


$llk = Llk::load(new Read(__DIR__ . '/../resources/grammar.pp'));

try {
    print_s($llk, @file_get_contents(__DIR__ . '/../tests/.resources/ast-ab-spec-tests/+comments.graphqls'));
} catch (Throwable $e) {
    echo str_repeat('=', 79) . "\n" .
        ' |    ' . "\n" .
        ' |    ' .
            str_replace("\n", "\n |    ", $e->getMessage()) . "\n" .
        ' |    ' . $e->getFile() . ':' . $e->getLine() . "\n" .
        ' |    ' . "\n" .
    str_repeat('=', 79) . "\n";
}
