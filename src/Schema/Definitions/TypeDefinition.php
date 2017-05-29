<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Definitions;

/**
 * Class TypeDefinition
 * @package Serafim\Railgun\Schema\Definitions
 */
class TypeDefinition implements TypeDefinitionInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $list;

    /**
     * TypeDefinition constructor.
     * @param string $type
     * @param bool $isNullable
     * @param bool $isList
     */
    public function __construct(string $type, bool $isNullable = true, bool $isList = false)
    {
        $this->type = $type;
        $this->nullable = $isNullable;
        $this->list = $isList;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->list;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->type;
    }
}
