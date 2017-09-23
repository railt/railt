<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Directive;

use Railt\Reflection\Base\Behavior\BaseChild;
use Railt\Reflection\Base\Behavior\BaseName;
use Railt\Reflection\Base\Support\Resolving;
use Railt\Reflection\Contracts\Types\Directive\Argument;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\DirectiveType;

/**
 * Class BaseInvocation
 */
abstract class BaseInvocation implements DirectiveInvocation
{
    use BaseName;
    use BaseChild;
    use Resolving;

    /**
     * @var DirectiveType
     */
    protected $directive;

    /**
     * @var array|Argument[]
     */
    protected $arguments = [];

    /**
     * @return DirectiveType
     */
    public function getDirective(): DirectiveType
    {
        return $this->compiled()->directive;
    }

    /**
     * @return iterable
     */
    public function getArguments(): iterable
    {
        return \array_values($this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return null|Argument
     */
    public function getArgument(string $name): ?Argument
    {
        return $this->compiled()->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->compiled()->arguments);
    }
}
