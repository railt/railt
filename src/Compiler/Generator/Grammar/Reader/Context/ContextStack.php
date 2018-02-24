<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Context;

use Railt\Compiler\Generator\Grammar\Reader\Productions\Group;

/**
 * Class ContextStack
 */
class ContextStack
{
    /**
     * @var \SplStack|Group[]
     */
    private $groups;

    /**
     * @var \SplStack|Group[]
     */
    private $closed;

    /**
     * @var \SplStack|Group[]
     */
    private $context;

    /**
     * @var Group
     */
    private $root;

    /**
     * ContextStack constructor.
     * @param Group $root
     */
    public function __construct(Group $root)
    {
        $this->groups  = new \SplStack();
        $this->closed  = new \SplStack();
        $this->context = new \SplStack();

        $this->groups->push($root);
        $this->context->push($root);
        $this->root = $root;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function is(string $type): bool
    {
        return $this->current()->is($type);
    }

    /**
     * @return Group
     */
    public function root(): Group
    {
        return $this->root;
    }

    /**
     * @param Group $group
     */
    public function push(Group $group): void
    {
        $this->context->push($group);

        if ($group->is(Group::class)) {
            $this->groups->push($group);
        }
    }

    /**
     * @return Group
     */
    public function current(): Group
    {
        return $this->context->top();
    }

    /**
     * @return Group
     */
    public function group(): Group
    {
        return $this->groups->top();
    }

    /**
     * @return int
     */
    public function groups(): int
    {
        return $this->groups->count();
    }

    /**
     * @return Group
     */
    public function popContext(): Group
    {
        $group = $this->context->pop();

        if ($group->is(Group::class)) {
            $this->groups->pop();
            $this->closed->push($group);
        }

        return $group;
    }

    /**
     * @return Group
     * @throws \OutOfBoundsException
     */
    public function popGroup(): Group
    {
        while ($this->context->count() > 0) {
            if (($ctx = $this->popContext())->is(Group::class)) {
                $this->closed->push($ctx);
                return $ctx;
            }
        }

        throw new \OutOfBoundsException('Context is empty');
    }

    /**
     * @return Group
     */
    public function previousGroup(): Group
    {
        return $this->closed->top();
    }
}
