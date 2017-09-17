<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Reflection\Contracts\DefinitionInterface;
use Railt\Reflection\Contracts\Type\TypeInterface;

/**
 * Interface BuilderInterface
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
