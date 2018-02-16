<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Compiler\Grammar\Reader;
use Railt\Io\File;
use Railt\Lexer\Lexer;
use Railt\Lexer\Tokens\Channel;
use Railt\Lexer\Tokens\Output as T;

require __DIR__ . '/../vendor/autoload.php';

$reader = new Reader(File::fromPathname(__DIR__ . '/grammar/sdl.pp'));


$lexer = (new Lexer(
    $reader->getTokenDefinitions(),
    $reader->getPragmaDefinitions()->lexerConfiguration()
))
    ->read(File::fromPathname(__DIR__ . '/sdl.graphqls'))
    ->exceptChannel(Channel::SKIPPED);

foreach ($lexer as $token) {
    echo \vsprintf('%3d: %-20s | %s', [
            $token[T::I_TOKEN_OFFSET],
            $token[T::I_TOKEN_NAME],
            $token[T::I_TOKEN_BODY],
        ]) . "\n";
}
