<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Creators;

use Serafim\Railgun\Schema\Definitions\TypeDefinition;
use Serafim\Railgun\Schema\Definitions\TypeDefinitionInterface;

/**
 * Class TypeDefinitionCreator
 * @package Serafim\Railgun\Schema\Creators
 */
class TypeDefinitionCreator implements CreatorInterface
{
    use ProvidesTypeDefinition;

    /**
     * @var string
     */
    private $type;

    /**
     * TypeDefinitionCreator constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return TypeDefinitionInterface
     */
    public function build(): TypeDefinitionInterface
    {
        return new TypeDefinition($this->type, $this->isNullable, $this->isList);
    }
}
