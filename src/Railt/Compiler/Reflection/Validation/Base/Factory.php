<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Base;

/**
 * Class Factory
 */
class Factory extends BaseValidator
{
    /**
     * @var array|ValidatorInterface[]
     */
    private $items = [];

    /**
     * @var \Closure|null
     */
    private $matcher;

    /**
     * @param \Closure $matcher
     * @return Factory
     */
    public function setMatcher(\Closure $matcher): Factory
    {
        $this->matcher = $matcher;

        return $this;
    }

    /**
     * @param string $item
     * @return Factory
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function addValidator(string $item): Factory
    {
        if (! \is_subclass_of($item, ValidatorInterface::class)) {
            $error = \sprintf('%s must be instance of %s', $item, ValidatorInterface::class);
            throw new \InvalidArgumentException($error);
        }

        $this->items[] = new $item($this->validator, $this->getCallStack(), $this->getGroupName());

        return $this;
    }

    /**
     * @param array ...$args
     * @return void
     */
    public function validate(...$args): void
    {
        foreach ($this->items as $item) {
            if ($this->match($item, ...$args)) {
                $item->validate(...$args);
            }
        }
    }

    /**
     * @param ValidatorInterface $validator
     * @param array ...$args
     * @return bool
     */
    private function match(ValidatorInterface $validator, ...$args): bool
    {
        if ($this->matcher !== null) {
            return ($this->matcher)($validator, ...$args);
        }

        return true;
    }
}

