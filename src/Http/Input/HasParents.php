<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

use Railt\Http\InputInterface;

/**
 * Trait HasParents
 * @mixin ProvideParents
 */
trait HasParents
{
    /**
     * @var null|\Closure
     */
    protected $parentResolver;

    /**
     * @var null|\Closure
     */
    protected $parentInputResolver;

    /**
     * @param int $depth
     * @return mixed
     */
    public function getParent(int $depth = 0)
    {
        if ($this->parentResolver) {
            return ($this->parentResolver)($depth, $this);
        }

        return null;
    }

    /**
     * @param \Closure|null $resolver
     * @return ProvideParents
     */
    public function withParent(?\Closure $resolver): ProvideParents
    {
        $this->parentResolver = $resolver;

        return $this;
    }

    /**
     * @param int $depth
     * @return null|InputInterface
     */
    public function getParentInput(int $depth = 0): ?InputInterface
    {
        if ($this->parentInputResolver) {
            return ($this->parentInputResolver)($depth, $this);
        }

        return null;
    }

    /**
     * @param \Closure|null $resolver
     * @return ProvideParents
     */
    public function withParentInput(?\Closure $resolver): ProvideParents
    {
        $this->parentInputResolver = $resolver;

        return $this;
    }
}
