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
 * Interface TypeLoaderInterface
 */
interface TypeLoaderInterface
{
    /**
     * @param string $type
     * @return mixed
     */
    public function resolve(string $type);

    /**
     * @param TypeInterface $definition
     * @param string $class
     * @return mixed
     */
    public function make(TypeInterface $definition, string $class);
}
