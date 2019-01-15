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
 * Trait HasVariables
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
        /**
         * Support sampling from an array using the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-get
         */
        if (\function_exists('\\array_get')) {
            return \array_get($this->variables, $name, $default);
        }

        return $this->variables[$name] ?? $default;
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
            /**
             * Support insertion into an array using the helper of Illuminate Framework.
             * @see https://laravel.com/docs/5.7/helpers#method-array-set
             */
            if (\function_exists('\\array_set')) {
                \array_set($this->variables, $name, $value);

                return $this;
            }

            $this->variables[$name] = $value;
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
        /**
         * Support for checking an element in an array when used the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.7/helpers#method-array-has
         */
        if (\function_exists('\\array_has')) {
            return \array_has($this->variables, $name);
        }

        return isset($this->variables[$name]) || \array_key_exists($name, $this->variables);
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
