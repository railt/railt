<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Type;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use Railt\CodeGenerator\FieldGenerator;
use Railt\Common\Iter;

/**
 * @property-read ObjectTypeInterface $type
 */
class ObjectTypeGenerator extends TypeGenerator
{
    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->renderDescription($this->type, true) .
            $this->line($this->renderHeader(), $this->depth()) . ' ' . $this->renderFields();
    }

    /**
     * @return string
     */
    private function renderHeader(): string
    {
        $result = 'type ' . $this->type->getName();

        $interfaces = \array_map(fn(InterfaceTypeInterface $interface): string => $interface->getName(),
            Iter::toArray($this->type->getInterfaces()));

        if ($interfaces !== []) {
            $result .= ' implements ' . \implode(' & ', $interfaces);
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function renderFields(): string
    {
        $result = [];

        foreach ($this->type->getFields() as $field) {
            $generator = new FieldGenerator($field, $this->config([
                FieldGenerator::CONFIG_DEPTH     => $this->depth() + 1,
                FieldGenerator::CONFIG_MULTILINE => true,
            ]));

            $result[] = $generator->toString();
        }

        if ($result === []) {
            return '';
        }

        return
            $this->line($this->isNewLineBraces() ? "\n{" : '{', $this->depth()) . "\n" .
            $this->fields($result, 0) . "\n" .
            $this->line('}', $this->depth());
    }

    /**
     * @param array|string[] $lines
     * @param int $depth
     * @return string
     */
    protected function fields(array $lines, int $depth): string
    {
        $lines = \array_map(fn(string $line): string => $this->line($line, $depth), $lines);

        return \implode("\n", $lines);
    }
}
