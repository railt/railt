<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Schema;
use Railt\SDL\DictionaryInterface;
use Railt\TypeSystem\Bridge\Webonyx\Internal\BuilderFactory;

final class Factory implements FactoryInterface
{
    public function build(DictionaryInterface $types): Schema
    {
        $factory = new BuilderFactory();

        return $factory->getSchema($types);
    }
}
