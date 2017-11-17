<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Reflection\Contracts\Types\TypeDefinition;

/**
 * Interface BuilderInterface.
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param AdapterInterface $parent
     * @param TypeDefinition $type
     */
    public function __construct(AdapterInterface $parent, TypeDefinition $type);

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition;

    /**
     * @return mixed
     */
    public function build();
}
