<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http;

/**
 * Interface Identifiable
 */
interface Identifiable
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return Identifiable|$this
     */
    public function withId(int $id): self;
}
