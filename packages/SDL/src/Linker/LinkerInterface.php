<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Linker;

use Railt\SDL\Ast\Location;

/**
 * Interface LinkerInterface
 */
interface LinkerInterface
{
    /**
     * @var int
     */
    public const LINK_ENUM_TYPE = 2;

    /**
     * @var int
     */
    public const LINK_INPUT_OBJECT_TYPE = 4;

    /**
     * @var int
     */
    public const LINK_INTERFACE_TYPE = 8;

    /**
     * @var int
     */
    public const LINK_OBJECT_TYPE = 16;

    /**
     * @var int
     */
    public const LINK_SCALAR_TYPE = 32;

    /**
     * @var int
     */
    public const LINK_UNION_TYPE = 64;

    /**
     * @var int
     */
    public const LINK_TYPE = self::LINK_ENUM_TYPE | self::LINK_INPUT_OBJECT_TYPE |
        self::LINK_INTERFACE_TYPE | self::LINK_OBJECT_TYPE | self::LINK_SCALAR_TYPE |
        self::LINK_UNION_TYPE;

    /**
     * @var int
     */
    public const LINK_DIRECTIVE = 128;

    /**
     * @var int
     */
    public const LINK_SCHEMA = 256;

    /**
     * @param string|null $name
     * @param int $type
     * @param Location $from
     * @return void
     */
    public function __invoke(?string $name, int $type, Location $from): void;
}
