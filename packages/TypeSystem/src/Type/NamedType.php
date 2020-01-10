<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;

/**
 * {@inheritDoc}
 */
abstract class NamedType extends Type implements NamedTypeInterface
{
    use NameTrait;
    use DescriptionTrait;

    /**
     * NamedType constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        $this->setName($name);

        $this->fill($properties, [
            'description' => fn(?string $description) => $this->setDescription($description),
        ]);
    }
}
