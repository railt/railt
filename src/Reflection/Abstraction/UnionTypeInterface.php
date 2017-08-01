<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Interface UnionTypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface UnionTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface
{
    /**
     * @return iterable|NamedDefinitionInterface[]
     */
    public function getTypes(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool;

    /**
     * @param string $name
     * @return null|NamedDefinitionInterface
     */
    public function getType(string $name): ?NamedDefinitionInterface;
}
