<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

/**
 * Class Group
 */
class Group extends Rule implements \IteratorAggregate
{
    /**
     * @var \SplQueue
     */
    private $children;

    /**
     * @var Group
     */
    private $parent;

    /**
     * Group constructor.
     * @param Group|null $parent
     * @param string|null $name
     */
    public function __construct(?self $parent, string $name = null)
    {
        $this->parent   = $parent;
        $this->children = new \SplQueue();

        if ($name) {
            $this->name = $name;
        }
    }

    /**
     * @return Group
     */
    public function parent(): self
    {
        return $this->parent;
    }

    /**
     * @param Rule $rule
     */
    public function push(Rule $rule): void
    {
        $this->children->push($rule);
    }

    /**
     * @return null|Rule
     */
    public function pop(): ?Rule
    {
        if (\count($this->children)) {
            return $this->children->pop();
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->children->count() === 0;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return $this->children;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function __debugInfo(): array
    {
        return \array_merge(parent::__debugInfo(), [
            'children' => \iterator_to_array($this->children),
        ]);
    }
}
