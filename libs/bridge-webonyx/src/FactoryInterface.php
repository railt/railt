<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx;

use GraphQL\Type\Schema;
use Railt\SDL\DictionaryInterface;

interface FactoryInterface
{
    public function build(DictionaryInterface $types): Schema;
}
