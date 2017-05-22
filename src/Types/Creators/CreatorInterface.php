<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Creators;

/**
 * Interface CreatorInterface
 * @package Serafim\Railgun\Types\Creators
 */
interface CreatorInterface
{
    /**
     * CreatorInterface constructor.
     * @param string $typeName
     * @param null|string $name
     */
    public function __construct(string $typeName, ?string $name = null);
}
