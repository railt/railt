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
 * Trait VariablesTrait
 */
trait VariablesTrait
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getVariable(string $name, $default = null)
    {
        return $this->variables[$name] ?? $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool
    {
        return isset($this->variables[$name]) || \array_key_exists($name, $this->variables);
    }
}
