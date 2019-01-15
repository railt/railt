<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Interact;

/**
 * Trait InteractWithEnvironment
 */
trait InteractWithEnvironment
{
    /**
     * @var array
     */
    protected $env = [];

    /**
     * @return void
     */
    private function bootInteractWithEnvironment(): void
    {
        $this->env = \getenv();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return InteractWithEnvironment|$this
     */
    protected function withEnv(string $name, $value): self
    {
        $this->env[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    protected function env(string $name, $default = null)
    {
        return $this->env[$name] ?? $default;
    }
}
