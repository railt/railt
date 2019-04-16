<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Building;

use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Interface ProvidesTypeDefinition
 */
interface ProvidesTypeDefinition
{
    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition;
}
