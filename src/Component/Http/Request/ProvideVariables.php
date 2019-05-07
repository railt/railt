<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Request;

/**
 * Interface ProvideVariables
 */
interface ProvideVariables
{
    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getVariable(string $name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $rewrite
     * @return ProvideVariables|$this
     */
    public function withVariable(string $name, $value, bool $rewrite = false): self;

    /**
     * @param array $variables
     * @param bool $rewrite
     * @return ProvideVariables|$this
     */
    public function withVariables(array $variables, bool $rewrite = false): self;

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool;

    /**
     * @return array
     */
    public function getVariables(): array;
}
