<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface TypeLoaderInterface
 * @package Railt\Adapters
 */
interface TypeLoaderInterface
{
    /**
     * @param string $type
     * @return mixed
     */
    public function resolve(string $type);

    /**
     * @param DefinitionInterface|TypeInterface $definition
     * @param string $class
     * @return mixed
     */
    public function make($definition, string $class);
}
