<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Creators;

/**
 * Trait ProvidesTypeDefinition
 * @package Serafim\Railgun\Schema\Creators
 * @mixin ListSupportsInterface
 */
trait ProvidesTypeDefinition
{
    /**
     * @var bool
     */
    protected $isNullable = true;

    /**
     * @var bool
     */
    protected $isList = false;

    /**
     * @return CreatorInterface|$this
     */
    public function many(): CreatorInterface
    {
        $this->isList = true;

        return $this;
    }

    /**
     * @return CreatorInterface|$this
     */
    public function nullable(): CreatorInterface
    {
        $this->isNullable = true;

        return $this;
    }

    /**
     * @return CreatorInterface|$this
     */
    public function required(): CreatorInterface
    {
        $this->isNullable = false;

        return $this;
    }

    /**
     * @return CreatorInterface|$this
     */
    public function single(): CreatorInterface
    {
        $this->isList = false;

        return $this;
    }
}
