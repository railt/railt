<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\TypeSystem\Common\ArgumentsTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;
use Serafim\Immutable\Immutable;

/**
 * {@inheritDoc}
 */
final class Directive extends Definition implements DirectiveInterface
{
    use NameTrait;
    use ArgumentsTrait;
    use DescriptionTrait;

    /**
     * @psalm-var array<int, string>
     * @var array|string[]
     */
    protected array $locations = [];

    /**
     * @var bool
     */
    protected bool $repeatable = false;

    /**
     * Directive constructor.
     *
     * @param string $name
     * @param iterable $properties
     * @throws \Throwable
     */
    public function __construct(string $name, iterable $properties = [])
    {
        $this->setName($name);

        $this->fill($properties, [
            'arguments'   => fn(iterable $arguments) => $this->addArguments($arguments),
            'description' => fn(?string $description) => $this->setDescription($description),
            'locations'   => fn(iterable $locations) => $this->addLocations($locations),
            'repeatable'  => fn(bool $repeatable) => $this->setRepeatable($repeatable),
        ]);
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|string[] $locations
     * @return void
     */
    public function addLocations(iterable $locations): void
    {
        foreach ($locations as $location) {
            $this->addLocation($location);
        }
    }

    /**
     * @param string $location
     * @return object|self|$this
     */
    public function addLocation(string $location): self
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isRepeatable(): bool
    {
        return $this->repeatable;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param bool $repeatable
     * @return void
     */
    public function setRepeatable(bool $repeatable = true): void
    {
        $this->repeatable = $repeatable;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param bool $allowRepeats
     * @return object|self|$this
     */
    public function withRepeatable(bool $allowRepeats = true): self
    {
        return Immutable::execute(fn() => $this->setRepeatable($allowRepeats));
    }

    /**
     * {@inheritDoc}
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param iterable|string[] $locations
     * @return object|self|$this
     */
    public function withLocations(iterable $locations): self
    {
        return Immutable::execute(fn() => $this->addLocations($locations));
    }

    /**
     * @param string $location
     * @return object|self|$this
     */
    public function withLocation(string $location): self
    {
        return Immutable::execute(fn() => $this->addLocation($location));
    }

    /**
     * {@inheritDoc}
     */
    public function hasLocation(string $name): bool
    {
        return \in_array($name, $this->locations, true);
    }

    /**
     *
     * @param string $location
     * @return object|self|$this
     */
    public function withoutLocation(string $location): self
    {
        return Immutable::execute(fn() => $this->removeLocation($location));
    }

    /**
     * @param string $location
     * @return object|self|$this
     */
    public function removeLocation(string $location): self
    {
        $index = \array_search($location, $this->locations, true);

        if ($index !== false) {
            unset($this->locations[$index]);
        }

        return $this;
    }
}
