<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Type\InputTypeInterface;

/**
 * Class Value
 */
class Value extends Definition
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var InputTypeInterface
     */
    public InputTypeInterface $type;
}
