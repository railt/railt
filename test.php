<?php

use Railt\Introspection\Client;
use Railt\Introspection\Origin\UriOrigin;

require __DIR__ . '/vendor/autoload.php';

$client = new Client();
$schema = $client->read(new UriOrigin('https://ru.railt.org/graphql'));

foreach ($schema->getTypeMap() as $type) {
    dump($type);
}
