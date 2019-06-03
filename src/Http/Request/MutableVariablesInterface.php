<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface MutableVariablesInterface
 */
interface MutableVariablesInterface extends VariablesInterface
{
    /**
     * @param string $name
     * @param mixed $value
     * @return MutableVariablesInterface|$this
     */
    public function withVariable(string $name, $value): self;

    /**
     * @param string $name
     * @return MutableVariablesInterface|$this
     */
    public function withoutVariable(string $name): self;

    /**
     * @param iterable $variables
     * @return MutableVariablesInterface|$this
     */
    public function withVariables(iterable $variables): self;

    /**
     * @param iterable $variables
     * @return MutableVariablesInterface|$this
     */
    public function setVariables(iterable $variables): self;
}
