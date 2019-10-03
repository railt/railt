<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Config;

/**
 * Class MutableRepository
 */
class MutableRepository extends Repository implements MutableRepositoryInterface
{
    /**
     * @var array|\Closure[]
     */
    private array $observers = [];

    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value = null): void
    {
        $result =& $this->items;

        $chunks = $this->chunks($key);

        while (\count($chunks) > 1) {
            $current = \array_shift($chunks);

            if (! isset($result[$current]) || ! \is_array($result[$current])) {
                $result[$current] = [];
            }

            $result = &$result[$current];
        }

        $result[\array_shift($chunks)] = $value;

        $this->fireObservers();
    }

    /**
     * {@inheritDoc}
     */
    public function merge(RepositoryInterface $repository): void
    {
        $this->items = \array_merge_recursive($this->items, $repository->all());

        $this->fireObservers();
    }

    /**
     * @return void
     */
    private function fireObservers(): void
    {
        foreach ($this->observers as $observer) {
            $observer($this);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onUpdate(\Closure $then): void
    {
        $this->observers[] = $then;
    }
}
