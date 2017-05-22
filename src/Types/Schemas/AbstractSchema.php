<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Schemas;

use Serafim\Railgun\Contracts\SchemaInterface;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Types\TypesRegistry;

/**
 * Class AbstractSchema
 * @package Serafim\Railgun\Types\Schemas
 */
abstract class AbstractSchema implements SchemaInterface
{
    use Extendable;
    use InteractWithName;

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
     * @return mixed
     */
    public function id()
    {
        return $this->make(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @param string $type
     * @return mixed
     */
    protected function make(string $type)
    {
        return new $this->creator($type, $this->formatName($type));
    }

    /**
     * @return mixed
     */
    public function ids()
    {
        return $this->list(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @param string $type
     * @return mixed
     */
    protected function list(string $type)
    {
        return $this->make($type)->many();
    }

    /**
     * @return mixed
     */
    public function integer()
    {
        return $this->make(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return mixed
     */
    public function integers()
    {
        return $this->list(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return mixed
     */
    public function string()
    {
        return $this->make(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return mixed
     */
    public function strings()
    {
        return $this->list(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return mixed
     */
    public function boolean()
    {
        return $this->make(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return mixed
     */
    public function booleans()
    {
        return $this->list(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return mixed
     */
    public function float()
    {
        return $this->make(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }

    /**
     * @return mixed
     */
    public function floats()
    {
        return $this->list(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }
}
