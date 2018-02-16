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

require __DIR__ . '/../vendor/autoload.php';

$reader = new Reader(File::fromPathname(__DIR__ . '/grammar/sdl.pp'));

foreach ($reader->getRuleDefinitions() as $i => $obj) {
    echo \sprintf('%20s | %s ', $i, \get_class($obj)) . "\n";
}
