<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\ArgumentsTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;

/**
 * {@inheritDoc}
 */
class Directive extends Definition implements DirectiveInterface
{
    use NameTrait;
    use ArgumentsTrait;
    use DescriptionTrait;

    /**
     * @psalm-var array<int, string>
     * @var array|string[]
     */
    public array $locations = [];

    /**
     * @var bool
     */
    public bool $isRepeatable = false;

    /**
     * {@inheritDoc}
     */
    public function isRepeatable(): bool
    {
        return $this->isRepeatable;
    }

    /**
     * {@inheritDoc}
     */
    public function hasLocation(string $name): bool
    {
        return \in_array($name, $this->locations, true);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }
}
