<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Events;

use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ArgumentResolving
 */
class ArgumentResolving extends Event
{
    /**
     * @var ArgumentDefinition
     */
    private $argument;

    /**
     * @var mixed
     */
    private $value;

    /**
     * ArgumentResolving constructor.
     * @param ArgumentDefinition $argument
     * @param $value
     */
    public function __construct(ArgumentDefinition $argument, $value)
    {
        $this->argument = $argument;
        $this->value    = $value;
    }

    /**
     * @return ArgumentDefinition
     */
    public function getArgument(): ArgumentDefinition
    {
        return $this->argument;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}