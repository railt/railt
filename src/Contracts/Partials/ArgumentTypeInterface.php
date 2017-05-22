<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Partials;

use Serafim\Railgun\Contracts\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Types\Schemas\TypeDefinition;

/**
 * Interface ArgumentTypeInterface
 * @package Serafim\Railgun\Contracts\Partials
 */
interface ArgumentTypeInterface extends TypeInterface
{
    /**
     * @param TypeDefinition $schema
     * @return TypeDefinitionInterface
     */
    public function getType(TypeDefinition $schema): TypeDefinitionInterface;

    /**
     * @return mixed|null
     */
    public function getDefaultValue();
}
