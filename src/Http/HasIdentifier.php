<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Trait HasIdentifier
 * @mixin Identifiable
 */
trait HasIdentifier
{
    /**
     * @var int
     */
    private static $lastId = 0;

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
            $this->id = self::$lastId++;
        }

        return $this->id;
    }

    /**
     * @param int $id
     * @return Identifiable|$this
     */
    public function withId(int $id): Identifiable
    {
        $this->id = $id;

        return $this;
    }
}
