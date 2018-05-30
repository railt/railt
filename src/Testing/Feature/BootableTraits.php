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
        foreach ($this->traits(true) as $trait) {
            $bootMethod = 'boot' . $trait;

            if (\method_exists($this, $bootMethod)) {
                $this->{$bootMethod}();
            }
        }
    }

    /**
     * @param bool $shortName
     * @return \Traversable
     */
    private function traits(bool $shortName = true): \Traversable
    {
        $traits = [];

        $parents = \class_parents(static::class);
        $class = static::class;

        while ($class !== null) {
            $traits += \array_values(\class_uses($class));

            $class = \count($parents) ? \array_shift($parents) : null;
        }

        yield from \array_unique($traits);
    }

    /**
     * @return void
     */
    protected function tearDownTestKernel(): void
    {
        foreach ($this->traits(true) as $trait) {
            $destroyMethod = 'destroy' . $trait;

            if (\method_exists($this, $destroyMethod)) {
                $this->{$destroyMethod}();
            }
        }
    }
}
