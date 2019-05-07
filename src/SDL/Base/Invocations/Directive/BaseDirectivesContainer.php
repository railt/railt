<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Invocations\Directive;

use Railt\SDL\Contracts\Invocations\Directive\HasDirectives;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;

/**
 * Trait BaseDirectivesContainer
 *
 * @mixin HasDirectives
 */
trait BaseDirectivesContainer
{
    /**
     * Internal structure will look like this:
     * <code>
     *  protected $directives = [
     *      ['name' => DirectiveInvocation],
     *      ['name' => DirectiveInvocation],
     *      ['name' => DirectiveInvocation],
     *  ];
     * </code>
     *
     * @var array|DirectiveInvocation[]
     */
    protected $directives = [];

    /**
     * @param string|null $name
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(string $name = null): iterable
    {
        foreach ($this->directives as $definitions) {
            foreach ((array)$definitions as $found => $invocation) {
                if ($name === null || $name === $found) {
                    yield $found => $invocation;
                }
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        foreach ($this->getDirectives($name) as $directive) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation
    {
        foreach ($this->getDirectives($name) as $directive) {
            return $directive;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int
    {
        return \count($this->directives);
    }
}
