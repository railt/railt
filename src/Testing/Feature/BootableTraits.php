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
 * Trait BootableTraits
 */
trait BootableTraits
{
    /**
     * @return void
     */
    protected function setUpTestKernel(): void
    {
        foreach ($this->traits() as $trait) {
            $bootMethod = 'boot' . \class_basename($trait);

            if (\method_exists($this, $bootMethod)) {
                $this->{$bootMethod}();
            }
        }
    }

    /**
     * @return \Traversable
     */
    private function traits(): \Traversable
    {
        $traits = [];

        $parents = \class_parents(static::class);
        $class = static::class;

        while ($class !== null) {
            $traits = \array_merge(\array_values(\class_uses($class)), $traits);

            $class = \count($parents) ? \array_shift($parents) : null;
        }

        yield from \array_unique($traits);
    }

    /**
     * @return void
     */
    protected function tearDownTestKernel(): void
    {
        foreach ($this->traits() as $trait) {
            $destroyMethod = 'destroy' . \class_basename($trait);

            if (\method_exists($this, $destroyMethod)) {
                $this->{$destroyMethod}();
            }
        }
    }
}
