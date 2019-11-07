<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Constraint;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Common\TypeAwareInterface;

/**
 * @mixin TypeAwareInterface
 */
trait TypeTrait
{
    /**
     * @var TypeInterface
     */
    public TypeInterface $type;

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @param TypeInterface $type
     * @return void
     */
    protected function setType($type): void
    {
        $this->type = $type;

        $this->assertTypeTrait();
    }

    /**
     * @return void
     */
    protected function assertTypeTrait(): void
    {
        \assert(Constraint::isType($this->type));
    }
}
