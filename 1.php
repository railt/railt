<?php declare(strict_types=1);

use Railt\Io\File;
use Railt\SDL\Compiler\Pipeline;

require __DIR__ . '/vendor/autoload.php';

$sources = File::fromPathname(__DIR__ . '/gql/schema.graphqls');

try {
    $pipeline = new Pipeline();
    $r        = $pipeline->process($sources);
    dd($r);
} catch (Throwable $e) {
    echo $e;
}

