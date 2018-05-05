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
use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Store
 */
class Store
{
    /**
     * @var array|ObjectBox[]
     */
    private $data = [];

    /**
     * @param InputInterface $input
     * @param ObjectBox $box
     * @return ObjectBox
     */
    public function set(InputInterface $input, ObjectBox $box): ObjectBox
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
     * @param ObjectBox $box
     */
    private function setList(InputInterface $input, ObjectBox $box): void
    {
        $index = $this->current($input);

        if (! $this->has($input)) {
            $this->data[$index] = [];
        }

        $this->data[$index][] = $box;
    }

    /**
     * @param InputInterface $input
     * @param ObjectBox $box
     */
    private function setItem(InputInterface $input, ObjectBox $box): void
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

        if (! $this->containsScalar($field) && $field->isNonNull()) {
            return [];
        }

        return null;
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    private function containsScalar(FieldDefinition $field): bool
    {
        $type = $field->getTypeDefinition();

        return $type instanceof ScalarDefinition || $type instanceof EnumDefinition;
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
