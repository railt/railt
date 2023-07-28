<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx;

use GraphQL\Type\Schema;
use Railt\TypeSystem\DictionaryInterface;

interface FactoryInterface
{
    public function build(DictionaryInterface $types): Schema;
}
