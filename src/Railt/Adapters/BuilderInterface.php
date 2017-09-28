<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param AdapterInterface $parent
     * @param TypeInterface $type
     */
    public function __construct(AdapterInterface $parent, TypeInterface $type);

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return mixed
     */
    public function build();
}
