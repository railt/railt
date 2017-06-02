<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

use Serafim\Railgun\Types\Registry;
use Serafim\Railgun\Schema\Creators\CreatorInterface;

/**
 * Class AbstractSchema
 * @package Serafim\Railgun\Schema
 */
abstract class AbstractSchema implements SchemaInterface
{
    use Extendable;

    /**
     * @var string
     */
    private $creator;

    /**
     * AbstractSchema constructor.
     * @param string $creator
     */
    protected function __construct(string $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @param string $type
     * @return CreatorInterface
     */
    public function typeOf(string $type): CreatorInterface
    {
        assert($this->creator !== null, 'Creator class must be declared');

        return new $this->creator($type);
    }

    /**
     * @param string $type
     * @return CreatorInterface
     */
    public function listOf(string $type): CreatorInterface
    {
        return $this->typeOf($type)->many();
    }

    /**
     * @return CreatorInterface
     */
    public function id(): CreatorInterface
    {
        return $this->typeOf(Registry::INTERNAL_TYPE_ID);
    }

    /**
     * @return CreatorInterface
     */
    public function ids(): CreatorInterface
    {
        return $this->listOf(Registry::INTERNAL_TYPE_ID);
    }

    /**
     * @return CreatorInterface
     */
    public function integer(): CreatorInterface
    {
        return $this->typeOf(Registry::INTERNAL_TYPE_INT);
    }

    /**
     * @return CreatorInterface
     */
    public function integers(): CreatorInterface
    {
        return $this->listOf(Registry::INTERNAL_TYPE_INT);
    }

    /**
     * @return CreatorInterface
     */
    public function string(): CreatorInterface
    {
        return $this->typeOf(Registry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return CreatorInterface
     */
    public function strings(): CreatorInterface
    {
        return $this->listOf(Registry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return CreatorInterface
     */
    public function boolean(): CreatorInterface
    {
        return $this->typeOf(Registry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return CreatorInterface
     */
    public function booleans(): CreatorInterface
    {
        return $this->listOf(Registry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return CreatorInterface
     */
    public function float(): CreatorInterface
    {
        return $this->typeOf(Registry::INTERNAL_TYPE_FLOAT);
    }

    /**
     * @return CreatorInterface
     */
    public function floats(): CreatorInterface
    {
        return $this->listOf(Registry::INTERNAL_TYPE_FLOAT);
    }
}
