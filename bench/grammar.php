<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Compiler\Generator\Grammar\Reader;
use Railt\Io\File;

require __DIR__ . '/../vendor/autoload.php';

try {
    $reader = new Reader(File::fromPathname(__DIR__ . '/grammar/sdl.pp'));

    foreach ($reader->getRuleDefinitions() as $i => $obj) {
        //echo $obj . "\n\n";
    }
} catch (\Throwable $e) {
    echo $e;
}
