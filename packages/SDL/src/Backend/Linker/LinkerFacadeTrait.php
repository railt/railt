<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Linker;

/**
 * Trait LinkerFacadeTrait
 */
trait LinkerFacadeTrait
{
    /**
     * {@inheritDoc}
     */
    public function autoload(callable $loader): LinkerInterface
    {
        return $this->getLinker()
            ->autoload($loader);
    }

    /**
     * @return LinkerInterface
     */
    abstract public function getLinker(): LinkerInterface;

    /**
     * {@inheritDoc}
     */
    public function cancelAutoload(callable $loader): LinkerInterface
    {
        return $this->getLinker()
            ->cancelAutoload($loader);
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaders(): iterable
    {
        return $this->getLinker()
            ->getAutoloaders();
    }
}
