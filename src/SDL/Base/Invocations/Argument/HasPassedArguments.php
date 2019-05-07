<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Invocations\Argument;

/**
 * Trait HasPassedArguments
 */
trait HasPassedArguments
{
    /**
     * @var array|mixed[]
     */
    protected $arguments = [];

    /**
     * @return iterable
     */
    public function getPassedArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getPassedArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int
    {
        return \count($this->arguments);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // Arguments
            'arguments',
        ]);
    }
}
