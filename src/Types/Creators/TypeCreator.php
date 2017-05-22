<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Creators;

use Serafim\Railgun\Contracts\TypeDefinitionInterface;

/**
 * Class TypeCreator
 * @package Serafim\Railgun\Types\Creators
 */
class TypeCreator implements TypeDefinitionInterface, CreatorInterface
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
     * TypeCreator constructor.
     * @param string $type
     * @param null|string $name
     */
    public function __construct(string $type, ?string $name = null)
    {
        assert($name !== null, 'Type definition does not provide the "name" and "description"');

        $this->type = $type;
    }

    /**
     * @return TypeCreator
     */
    public function many(): TypeCreator
    {
        $this->isList = true;

        return $this;
    }

    /**
     * @return TypeCreator
     */
    public function nullable(): TypeCreator
    {
        $this->isNullable = true;

        return $this;
    }

    /**
     * @return TypeCreator
     */
    public function required(): TypeCreator
    {
        $this->isNullable = false;

        return $this;
    }

    /**
     * @return TypeCreator
     */
    public function single(): TypeCreator
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
