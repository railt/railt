<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Definitions;

use Serafim\Railgun\Contracts\Definitions\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Registrars\TypeDefinitionRegistrarInterface;

/**
 * Class TypeDefinition
 * @package Serafim\Railgun\Types\Definitions
 */
class TypeDefinition implements TypeDefinitionInterface, TypeDefinitionRegistrarInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $isNullable = true;

    /**
     * @var bool
     */
    private $isList = false;

    /**
     * FieldType constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return TypeDefinitionRegistrarInterface|TypeDefinition
     */
    public function many(): TypeDefinitionRegistrarInterface
    {
        $this->isList = true;

        return $this;
    }

    /**
     * @return TypeDefinitionRegistrarInterface|TypeDefinition
     */
    public function nullable(): TypeDefinitionRegistrarInterface
    {
        $this->isNullable = true;

        return $this;
    }

    /**
     * @return TypeDefinitionRegistrarInterface|TypeDefinition
     */
    public function notNull(): TypeDefinitionRegistrarInterface
    {
        $this->isNullable = false;

        return $this;
    }

    /**
     * @return TypeDefinitionRegistrarInterface|TypeDefinition
     */
    public function single(): TypeDefinitionRegistrarInterface
    {
        $this->isList = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->isList;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->type;
    }
}
