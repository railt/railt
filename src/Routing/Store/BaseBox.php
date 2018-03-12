<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;

/**
 * Class BaseBox
 */
abstract class BaseBox implements Box
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $serialized;

    /**
     * Box constructor.
     * @param mixed $data
     * @param mixed $serialized
     */
    public function __construct($data, $serialized)
    {
        $this->data       = $data;
        $this->serialized = $serialized;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->serialized;
    }

    /**
     * @param mixed $original
     * @param mixed $serialized
     * @return Box
     */
    public static function make($original, $serialized): Box
    {
        return new static($original, $serialized);
    }

    /**
     * @param array $items
     * @return ObjectBox|Box
     */
    public static function rebuild(array $items): Box
    {
        $data       = [];
        $serialized = [];

        /** @var ObjectBox $box */
        foreach ($items as $box) {
            $data[]       = $box->getValue();
            $serialized[] = $box->getResponse();
        }

        return static::make($data, $serialized);
    }
}
