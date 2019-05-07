<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Identifier\CollectorInterface;
use Railt\Http\Identifier\SharedCollector;

/**
 * Trait HasIdentifier
 *
 * @mixin Identifiable
 */
trait HasIdentifier
{
    /**
     * @var string|CollectorInterface
     */
    protected static $collector = SharedCollector::class;

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
            $this->id = static::$collector::next(static::class);
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
