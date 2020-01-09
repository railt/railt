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
use Railt\Common\Iter;
use Railt\TypeSystem\Common\ArgumentsTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;
use Serafim\Immutable\Immutable;

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
    protected array $locations = [];

    /**
     * @var bool
     */
    protected bool $repeatable = false;

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
    public function setRepeatable(bool $repeatable): void
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
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param iterable|string[] $locations
     * @return void
     */
    public function setLocations(iterable $locations): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->locations = Iter::toArray($locations, false);
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
        return Immutable::execute(fn() => $this->setLocations($locations));
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $location
     * @return object|self|$this
     */
    public function withLocation(string $location): self
    {
        return Immutable::execute(function () use ($location): void {
            if (! $this->hasLocation($location)) {
                $this->locations[] = $location;
            }
        });
    }

    /**
     * {@inheritDoc}
     */
    public function hasLocation(string $name): bool
    {
        return \in_array($name, $this->locations, true);
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string $location
     * @return object|self|$this
     */
    public function withoutLocation(string $location): self
    {
        return Immutable::execute(function () use ($location): void {
            $index = \array_search($location, $this->locations, true);

            if ($index !== false) {
                unset($this->locations[$index]);
            }
        });
    }
}
