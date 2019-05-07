<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

use Illuminate\Support\Arr;

/**
 * Trait HasVariables
 *
 * @mixin ProvideVariables
 */
trait HasVariables
{
    /**
     * @var array
     */
    private $variables = [];

    /**
     * @param string $name
     * @param mixed|null $default
     * @return null
     */
    public function getVariable(string $name, $default = null)
    {
        return Arr::get($this->variables, $name, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $rewrite
     * @return ProvideVariables|$this
     */
    public function withVariable(string $name, $value, bool $rewrite = false): ProvideVariables
    {
        if ($rewrite || ! $this->hasVariable($name)) {
            Arr::set($this->variables, $name, $value);
        }

        return $this;
    }

    /**
     * @param array $variables
     * @param bool $rewrite
     * @return ProvideVariables|$this
     */
    public function withVariables(array $variables, bool $rewrite = false): ProvideVariables
    {
        foreach ($variables as $name => $value) {
            $this->withVariable($name, $value, $rewrite);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool
    {
        return Arr::has($this->variables, $name);
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
