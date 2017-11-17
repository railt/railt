<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Invocations;

use Railt\Reflection\Base\Dependent\BaseDependent;
use Railt\Reflection\Contracts\Invocations\ArgumentInvocation;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Class BaseDirectiveInvocation.
 */
abstract class BaseDirectiveInvocation extends BaseDependent implements DirectiveInvocation
{
    /**
     * Directive type name.
     */
    protected const TYPE_NAME = 'Directive';

    /**
     * @var array|ArgumentInvocation[]
     */
    protected $arguments = [];

    /**
     * @return iterable|ArgumentInvocation[]
     */
    public function getPassedArguments(): iterable
    {
        return \array_values($this->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentInvocation
     */
    public function getPassedArgument(string $name): ?ArgumentInvocation
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int
    {
        return \count($this->arguments);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // Arguments
            'arguments',
        ]);
    }
}
