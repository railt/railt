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
use Railt\Compiler\Generator\Grammar\Reader\Productions\Rule;

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
     * ContextStack constructor.
     * @param Group $root
     */
    public function __construct(Group $root)
    {
        $this->groups = new \SplStack();
        $this->closed = new \SplStack();

        $this->push($root);
    }

    /**
     * @param Group $group
     */
    public function push(Group $group): void
    {
        $this->groups->push($group);
    }

    /**
     * @return Group
     */
    public function pop(): Group
    {
        $head = $this->groups->pop();
        $this->closed->push($head);

        return $head;
    }

    /**
     * @return Group
     */
    public function top(): Group
    {
        return $this->groups->top();
    }

    /**
     * @return Group
     */
    public function previous(): Group
    {
        return $this->closed->top();
    }
}
