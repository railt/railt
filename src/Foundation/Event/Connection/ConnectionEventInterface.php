<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Connection;

use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;

/**
 * Interface ConnectionEventInterface
 */
interface ConnectionEventInterface extends ProvidesConnection
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary;

    /**
     * @return SchemaDefinition
     */
    public function getSchema(): SchemaDefinition;
}
