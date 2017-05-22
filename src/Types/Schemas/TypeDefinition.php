<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Schemas;

use Serafim\Railgun\Types\Creators\TypeCreator;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;

/**
 * Class TypeDefinition
 * @package Serafim\Railgun\Types\Schemas
 *
 * @method TypeDefinitionInterface|TypeCreator id()
 * @method TypeDefinitionInterface|TypeCreator ids()
 * @method TypeDefinitionInterface|TypeCreator integer()
 * @method TypeDefinitionInterface|TypeCreator integers()
 * @method TypeDefinitionInterface|TypeCreator string()
 * @method TypeDefinitionInterface|TypeCreator strings()
 * @method TypeDefinitionInterface|TypeCreator boolean()
 * @method TypeDefinitionInterface|TypeCreator booleans()
 * @method TypeDefinitionInterface|TypeCreator float()
 * @method TypeDefinitionInterface|TypeCreator floats()
 *
 */
class TypeDefinition extends AbstractSchema
{
    /**
     * Fields constructor.
     */
    final public function __construct()
    {
        parent::__construct(TypeCreator::class);
    }

    /**
     * @param string $name
     * @return TypeDefinitionInterface|TypeCreator
     */
    public function typeOf(string $name): TypeDefinitionInterface
    {
        return parent::make($name);
    }

    /**
     * @param string $name
     * @return TypeDefinitionInterface|TypeCreator
     */
    public function listOf(string $name): TypeDefinitionInterface
    {
        return parent::list($name);
    }
}
