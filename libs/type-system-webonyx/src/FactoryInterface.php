<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Schema;
use Railt\SDL\DictionaryInterface;

interface FactoryInterface
{
    public function build(DictionaryInterface $types): Schema;
}
