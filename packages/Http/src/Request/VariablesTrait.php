<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Request;

use Railt\Common\Iter;
use Railt\Contracts\Http\Request\VariablesInterface;

/**
 * @mixin VariablesInterface
 */
trait VariablesTrait
{
    /**
     * @var array
     */
    protected array $variables = [];

    /**
     * @return array
     */
    public function getVariables(): array
    {
        \assert(\is_array($this->variables));

        return $this->variables;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getVariable(string $name, $default = null)
    {
        \assert(\is_array($this->variables));

        return $this->variables[$name] ?? $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool
    {
        \assert(\is_array($this->variables));

        return isset($this->variables[$name]) || \array_key_exists($name, $this->variables);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        \assert(\is_array($this->variables));

        return \count($this->variables);
    }

    /**
     * @param iterable|array|\Traversable $variables
     * @return void
     */
    private function setVariables(iterable $variables): void
    {
        $this->variables = Iter::toArray($variables, true);
    }
}
