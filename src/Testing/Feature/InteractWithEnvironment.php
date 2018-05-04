<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Feature;

/**
 * @property-read $this $debug
 * @property-read $this $production
 */
trait InteractWithEnvironment
{
    /**
     * @var bool
     */
    private $isDebug = true;

    /**
     * @return InteractWithEnvironment|$this
     */
    protected function debug(): self
    {
        $this->isDebug = true;

        return $this;
    }

    /**
     * @return InteractWithEnvironment|$this
     */
    protected function production(): self
    {
        $this->isDebug = false;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isDebug(): bool
    {
        return $this->isDebug;
    }
}
