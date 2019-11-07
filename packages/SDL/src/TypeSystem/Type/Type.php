<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use Railt\SDL\TypeSystem\Definition;

/**
 * {@inheritDoc}
 */
abstract class Type extends Definition implements TypeInterface
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_merge(['kind' => $this->getKind()], parent::jsonSerialize());
    }

    /**
     * @return string
     */
    abstract public function getKind(): string;
}
