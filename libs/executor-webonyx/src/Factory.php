<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx;

use GraphQL\Type\Schema;
use Railt\Executor\Webonyx\Builder\Internal\BuilderFactory;
use Railt\TypeSystem\DictionaryInterface;

final class Factory implements FactoryInterface
{
    public function build(DictionaryInterface $types): Schema
    {
        $factory = new BuilderFactory();

        return $factory->getSchema($types);
    }
}
