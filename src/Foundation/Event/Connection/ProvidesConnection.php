<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Connection;

use Railt\Component\Http\Identifiable;

/**
 * Interface ProvidesConnection
 */
interface ProvidesConnection
{
    /**
     * @return Identifiable
     */
    public function getConnection(): Identifiable;
}
