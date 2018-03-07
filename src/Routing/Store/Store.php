<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;

use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;

/**
 * Class Store
 */
class Store
{
    /**
     * @var array|Box[]
     */
    private $data = [];

    /**
     * @param InputInterface $input
     * @param Box $box
     * @return Box
     */
    public function set(InputInterface $input, Box $box): Box
    {
        if ($input->getFieldDefinition()->isList()) {
            $this->setList($input, $box);
        } else {
            $this->setItem($input, $box);
        }

        return $box;
    }

    /**
     * @param InputInterface $input
     * @return mixed|null
     */
    public function getParent(InputInterface $input)
    {
        return $this->data[$this->parent($input)] ?? null;
    }

    /**
     * @param InputInterface $input
     * @param Box $box
     */
    private function setList(InputInterface $input, Box $box): void
    {
        $index = $this->current($input);

        if (! $this->has($input)) {
            $this->data[$index] = [];
        }

        $this->data[$index][] = $box;
    }

    /**
     * @param InputInterface $input
     * @param Box $box
     */
    private function setItem(InputInterface $input, Box $box): void
    {
        $this->data[$this->current($input)] = $box;
    }

    /**
     * @param InputInterface $input
     * @return bool
     */
    public function has(InputInterface $input): bool
    {
        return \array_key_exists($this->current($input), $this->data);
    }

    /**
     * @param InputInterface $input
     * @return array|mixed
     */
    public function get(InputInterface $input)
    {
        return $this->data[$this->current($input)] ?? $this->default($input);
    }

    /**
     * @param InputInterface $input
     * @return array|null
     */
    private function default(InputInterface $input): ?array
    {
        $field = $input->getFieldDefinition();

        $scalar = $field->getTypeDefinition() instanceof ScalarDefinition;

        if (! $scalar && $field->isNonNull()) {
            return [];
        }

        return null;
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function parent(InputInterface $input): string
    {
        $parts = \explode(InputInterface::DEPTH_DELIMITER, $input->getPath());

        $parts = \array_slice($parts, 0, -1);

        return \implode(InputInterface::DEPTH_DELIMITER, $parts);
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function current(InputInterface $input): string
    {
        return $input->getPath();
    }
}
