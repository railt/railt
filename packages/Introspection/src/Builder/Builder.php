<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use Railt\Introspection\Exception\IntrospectionException;

/**
 * Class NamedTypeBuilder
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var RegistryInterface|Registry
     */
    protected RegistryInterface $registry;

    /**
     * @var bool
     */
    private bool $isCompleted = false;

    /**
     * @var NamedTypeInterface
     */
    private NamedTypeInterface $type;

    /**
     * @var array
     */
    private array $data;

    /**
     * NamedTypeBuilder constructor.
     *
     * @param RegistryInterface $registry
     * @param array $data
     * @throws \Throwable
     */
    public function __construct(RegistryInterface $registry, array $data)
    {
        $this->registry = $registry;
        $this->type = $this->create($data);
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return NamedTypeInterface
     * @throws \Throwable
     */
    private function create(array $data): NamedTypeInterface
    {
        $class = $this->getClass();

        return new $class([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getClass(): string;

    /**
     * @param string $kind
     * @return bool
     */
    public static function match(string $kind): bool
    {
        return $kind === static::getKind();
    }

    /**
     * @return string
     */
    abstract protected static function getKind(): string;

    /**
     * @return NamedTypeInterface
     * @throws \Throwable
     */
    public function getType(): NamedTypeInterface
    {
        if (! $this->isCompleted) {
            $this->isCompleted = true;

            $this->complete($this->type, $this->data);
        }

        return $this->type;
    }

    /**
     * @param NamedTypeInterface $type
     * @param array $data
     * @return void
     * @throws \Throwable
     */
    abstract protected function complete(NamedTypeInterface $type, array $data): void;

    /**
     * @param array $type
     * @return TypeInterface
     * @throws \Throwable
     */
    protected function type(array $type): TypeInterface
    {
        return $this->registry->type($type);
    }

    /**
     * @param string $type
     * @return NamedTypeInterface
     * @throws IntrospectionException
     */
    protected function get(string $type): NamedTypeInterface
    {
        return $this->registry->get($type);
    }
}
