<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * @var string
     */
    protected const PROPERTY_SETTER_PREFIX = 'set';

    /**
     * @var string
     */
    private const ERROR_PROPERTY_NOT_DEFINED = '%s type does not contain $%s attribute';

    /**
     * @var string
     */
    private const ERROR_PROPERTY_BAD_NAME = 'Property name must be a string, but %s given';

    /**
     * @var string
     */
    private const ERROR_PROPERTY_BAD_TYPE = 'Property %s::$%s cannot be determined by type %s';

    /**
     * Definition constructor.
     *
     * @psalm-param array<string, mixed> $properties
     *
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(iterable $properties = [])
    {
        $this->update($properties);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @psalm-suppress MixedAssignment
     * @psalm-return self
     * @psalm-param iterable<string, mixed> $properties
     *
     * @param array $properties
     * @return object|self|$this
     * @throws \Throwable
     */
    public function update(iterable $properties): self
    {
        foreach ($properties as $name => $value) {
            \assert(\is_string($name), \sprintf(self::ERROR_PROPERTY_BAD_NAME, \gettype($name)));

            switch (true) {
                // Fill property using setter.
                case \method_exists($this, $setter = $this->getSetterName($name)):
                    $this->fillUsingSetter($setter, $name, $value);
                    break;

                // Or using direct access instead.
                // Note: Perhaps this access method should be removed to
                //       implement readonly fields and improve access control.
                case \property_exists($this, $name):
                    $this->fillProperty($name, $value);
                    break;

                default:
                    $this->throwBadPropertyError($name);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getSetterName(string $name): string
    {
        return self::PROPERTY_SETTER_PREFIX . \ucfirst($name);
    }

    /**
     * @param string $setter
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function fillUsingSetter(string $setter, string $name, $value): void
    {
        try {
            $this->$setter($value);
        } catch (\TypeError $error) {
            if (\strpos($error->getMessage(), $setter . '() must be of the type') !== false) {
                $message = self::ERROR_PROPERTY_BAD_TYPE;
                $message = \sprintf($message, static::class, $name, \gettype($value));

                throw new \InvalidArgumentException($message);
            }

            throw $error;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function fillProperty(string $name, $value): void
    {
        try {
            $this->$name = $value;
        } catch (\TypeError $error) {
            if (\strpos($error->getMessage(), $name . ' must be') !== false) {
                $message = self::ERROR_PROPERTY_BAD_TYPE;
                $message = \sprintf($message, static::class, $name, \gettype($value));

                throw new \InvalidArgumentException($message);
            }

            throw $error;
        }
    }

    /**
     * @param string $name
     * @return void
     */
    private function throwBadPropertyError(string $name): void
    {
        $message = \sprintf(self::ERROR_PROPERTY_NOT_DEFINED, static::class, $name);

        throw new \InvalidArgumentException($message);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-param iterable<string, mixed> $properties
     * @psalm-return self
     *
     * @param iterable $properties
     * @return object|self|$this
     */
    public function with(iterable $properties): self
    {
        return Immutable::execute(fn() => $this->update($properties));
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $defaults = ['kind' => $this->getKind()];

        return \array_merge($defaults, \get_object_vars($this));
    }

    /**
     * @return string
     */
    protected function getKind(): string
    {
        $className = \basename(\str_replace('\\', '/', static::class));

        // If this class name ends with the "Type" suffix, then we delete it
        // and return the normal form of type "kind".
        return \substr($className, -4) === 'Type'
            ? \substr($className, 0, -4)
            : $className;
    }
}
