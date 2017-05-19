<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Support;

use Serafim\Railgun\Types\Creators\TypeCreator;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;

/**
 * Trait InteractWithTypes
 * @package Serafim\Railgun\Support
 */
trait InteractWithTypes
{
    /**
     * @param string $name
     * @return TypeDefinitionInterface|TypeCreator
     */
    public function typeOf(string $name): TypeDefinitionInterface
    {
        return new TypeCreator($name);
    }

    /**
     * @param string $name
     * @return TypeDefinitionInterface|TypeCreator
     */
    public function listOf(string $name): TypeDefinitionInterface
    {
        return $this->typeOf($name)->many();
    }
}
