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
 * Interface BuilderInterface
 * @package Railt\Adapters
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param AdapterInterface $parent
     * @param DefinitionInterface|TypeInterface $target
     */
    public function __construct(AdapterInterface $parent, $target);

    /**
     * @return DefinitionInterface|TypeInterface
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function build();
}
