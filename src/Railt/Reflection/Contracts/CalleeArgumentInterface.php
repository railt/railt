<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts;

/**
 * Interface CalleeArgumentInterface
 */
interface CalleeArgumentInterface extends NamedDefinitionInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return NamedDefinitionInterface
     */
    public function getType(): NamedDefinitionInterface;
}

