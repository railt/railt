<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http\Error;

use Railt\Contracts\Common\ArrayableInterface;
use Railt\Contracts\Common\JsonableInterface;
use Railt\Contracts\Common\RenderableInterface;

/**
 * Interface LocationInterface
 */
interface SourceLocationInterface extends
    ArrayableInterface,
    JsonableInterface,
    RenderableInterface
{
    /**
     * @var string
     */
    public const FIELD_LINE = 'line';

    /**
     * @var string
     */
    public const FIELD_COLUMN = 'column';

    /**
     * @return int
     */
    public function getLine(): int;

    /**
     * @return int
     */
    public function getColumn(): int;
}
