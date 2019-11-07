<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem;

use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use Railt\SDL\TypeSystem\Common\DeprecationTrait;
use Railt\SDL\TypeSystem\Common\DescriptionTrait;
use Railt\SDL\TypeSystem\Common\NameTrait;

/**
 * {@inheritDoc}
 */
class EnumValue extends Definition implements EnumValueInterface
{
    use NameTrait;
    use DescriptionTrait;
    use DeprecationTrait;

    /**
     * @var mixed
     */
    public $value;

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
