<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use Railt\TypeSystem\Common\DeprecationTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
final class EnumValue extends Definition implements EnumValueInterface
{
    use NameTrait;
    use DescriptionTrait;
    use DeprecationTrait;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * EnumValue constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        $this->setName($name);
        $this->setValue($name);

        $this->fill($properties, [
            'value'             => fn($value) => $this->setValue($value),
            'description'       => fn(?string $description) => $this->setDescription($description),
            'deprecationReason' => fn(?string $message) => $this->setDeprecationReason($message),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param mixed $value
     * @return void
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param mixed $value
     * @return object|self|$this
     */
    public function withValue($value): self
    {
        return Immutable::execute(fn() => $this->setValue($value));
    }
}
