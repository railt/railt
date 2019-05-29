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
 * Trait MutableVariablesTrait
 */
trait MutableVariablesTrait
{
    use VariablesTrait;

    /**
     * @param string $name
     * @param mixed $value
     * @return MutableVariablesInterface|$this
     */
    public function withVariable(string $name, $value): MutableVariablesInterface
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return MutableVariablesInterface|$this
     */
    public function withoutVariable(string $name): MutableVariablesInterface
    {
        unset($this->variables[$name]);

        return $this;
    }

    /**
     * @param array $variables
     * @return MutableVariablesInterface|$this
     */
    public function withVariables(array $variables): MutableVariablesInterface
    {
        $this->variables = \array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * @param array $variables
     * @return MutableVariablesInterface|$this
     */
    public function setVariables(array $variables): MutableVariablesInterface
    {
        $this->variables = $variables;

        return $this;
    }
}
