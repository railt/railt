<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Repository;

/**
 * Interface PackageInterface
 */
interface PackageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string|null $section
     * @return array
     */
    public function getExtra(string $section = null): array;

    /**
     * @return string
     */
    public function getDirectory(): string;
}
