<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx;

use GraphQL\Type\Schema;
use Railt\SDL\DictionaryInterface;
use Railt\Bridge\Webonyx\Builder\Internal\BuilderFactory;

final class Factory implements FactoryInterface
{
    public function build(DictionaryInterface $types): Schema
    {
        $factory = new BuilderFactory();

        return $factory->getSchema($types);
    }
}
