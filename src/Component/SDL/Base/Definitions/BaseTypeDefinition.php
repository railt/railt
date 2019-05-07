<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Definitions;

use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseTypeDefinition extends BaseDefinition implements TypeDefinition
{
    /**
     * Type definition name
     */
    protected const TYPE_NAME = '';

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        \assert(static::TYPE_NAME !== '', 'Type name must be initialized');

        return static::TYPE_NAME;
    }
}
