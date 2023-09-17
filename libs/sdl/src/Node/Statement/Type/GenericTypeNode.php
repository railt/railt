<?php

declare(strict_types=1);

namespace Railt\SDL\Node\Statement\Type;

use Railt\SDL\Node\Visitable;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class GenericTypeNode extends TypeNode
{
    public function __construct(
        #[Visitable]
        public TypeNode $type,
    ) {}
}
