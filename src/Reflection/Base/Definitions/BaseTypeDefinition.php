<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Definitions;

use Railt\Reflection\Base\BaseDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseTypeDefinition extends BaseDefinition implements TypeDefinition
{
    /**
     * @var string|null
     */
    protected $deprecation;

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecation !== null;
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return (string)$this->deprecation;
    }
}
