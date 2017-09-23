<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Enum;

use Railt\Reflection\Base\BaseNamedType;
use Railt\Reflection\Base\Behavior\BaseChild;
use Railt\Reflection\Contracts\Types\Enum\Value;

/**
 * Class BaseValue
 */
abstract class BaseValue extends BaseNamedType implements Value
{
    use BaseChild;

    /**
     * @var string
     */
    protected $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->compiled()->value;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'EnumValue';
    }
}
