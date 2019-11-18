<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Runtime;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;

use function GraphQL\TypeSystem\iterable_to_array;

/**
 * Class Execution
 */
abstract class Execution implements ExecutionInterface
{
    /**
     * @var DefinitionInterface
     */
    private DefinitionInterface $context;

    /**
     * @var iterable
     */
    private iterable $arguments;

    /**
     * Execution constructor.
     *
     * @param DefinitionInterface $context
     * @param iterable $arguments
     */
    public function __construct(DefinitionInterface $context, iterable $arguments = [])
    {
        $this->context = $context;
        $this->arguments = iterable_to_array($arguments, true);
    }

    /**
     * @return DefinitionInterface
     */
    public function getContext(): DefinitionInterface
    {
        return $this->context;
    }

    /**
     * @return iterable
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return isset($this->arguments[$name]) || \array_key_exists($name, $this->arguments);
    }
}
