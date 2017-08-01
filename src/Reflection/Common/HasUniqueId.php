<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

/**
 * Trait HasUniqueId
 * @package Serafim\Railgun\Reflection\Common
 */
trait HasUniqueId
{
    /**
     * @var int
     */
    protected static $lastId = 0;

    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        if ($this->id === null) {
            $this->id = ++self::$lastId;
        }

        return $this->id;
    }
}
