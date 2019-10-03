<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Repository;

use Railt\Discovery\Validator\RegistryInterface;

/**
 * Interface ReaderInterface
 */
interface ReaderInterface extends RegistryInterface
{
    /**
     * @return iterable|PackageInterface[]
     */
    public function getPackages(): iterable;

    /**
     * @return iterable|string[]
     */
    public function getExportedSections(): iterable;
}
