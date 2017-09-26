<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Directive;

use Railt\Reflection\Base\BaseNamedType;
use Railt\Reflection\Base\Behavior\BaseChild;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\Argument;

/**
 * Class BaseArgument
 */
abstract class BaseArgument extends BaseNamedType implements Argument
{
    use BaseChild;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var ArgumentType
     */
    protected $argument;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->resolve()->value;
    }

    /**
     * @return ArgumentType
     */
    public function getArgument(): ArgumentType
    {
        return $this->resolve()->argument;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Argument';
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'value',
            'parent',
            'argument',
        ]);
    }
}
