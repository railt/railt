<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\NameAwareInterface;

/**
 * @mixin NameAwareInterface
 */
trait NameTrait
{
    /**
     * @var string
     */
    public string $name;

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        $this->assertNameTrait();

        return $this->name;
    }

    /**
     * @return void
     */
    protected function assertNameTrait(): void
    {
        \assert(\is_string($this->name));
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        try {
            return $this->getName();
        } catch (\AssertionError $e) {
            return 'unknown';
        }
    }
}
