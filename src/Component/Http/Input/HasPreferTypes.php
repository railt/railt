<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Input;

/**
 * Trait HasPreferTypes
 *
 * @mixin ProvidePreferTypes
 */
trait HasPreferTypes
{
    /**
     * @var array|string[]
     */
    protected $preferTypes = [];

    /**
     * @return string
     */
    public function getPreferType(): string
    {
        $types = $this->getPreferTypes();

        return (string)\reset($types);
    }

    /**
     * @return iterable|string[]
     */
    public function getPreferTypes(): iterable
    {
        return $this->preferTypes;
    }

    /**
     * @param string ...$types
     * @return ProvidePreferTypes|$this
     */
    public function withPreferType(string ...$types): ProvidePreferTypes
    {
        $this->preferTypes = \array_merge($this->preferTypes, $types);
        $this->preferTypes = \array_unique($this->preferTypes);

        return $this;
    }

    /**
     * @param string ...$types
     * @return ProvidePreferTypes|$this
     */
    public function setPreferType(string ...$types): ProvidePreferTypes
    {
        $this->preferTypes = $types;

        return $this;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function wantsType(string $type): bool
    {
        return \in_array($type, $this->getPreferTypes(), true);
    }
}
