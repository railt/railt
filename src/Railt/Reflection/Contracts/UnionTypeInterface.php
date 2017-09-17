<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts;

use Railt\Reflection\Contracts\Common\HasDescription;
use Railt\Reflection\Contracts\Common\HasDirectivesInterface;

/**
 * Interface UnionTypeInterface
 */
interface UnionTypeInterface extends
    NamedDefinitionInterface,
    HasDirectivesInterface,
    HasDescription
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
