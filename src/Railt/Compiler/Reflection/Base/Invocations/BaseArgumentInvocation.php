<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Invocations;

use Railt\Compiler\Reflection\Base\Dependent\BaseDependent;
use Railt\Compiler\Reflection\Contracts\Invocations\ArgumentInvocation;

/**
 * Class BaseArgumentInvocation
 */
abstract class BaseArgumentInvocation extends BaseDependent implements ArgumentInvocation
{
    /**
     * Argument type name
     */
    protected const TYPE_NAME = 'Argument';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return mixed
     */
    public function getPassedValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // Value
            'value'
        ]);
    }
}
