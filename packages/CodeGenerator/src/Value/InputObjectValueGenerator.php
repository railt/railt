<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Value;

use Railt\Common\Iter;
use Railt\TypeSystem\Value\InputObjectValue;

/**
 * @property-read InputObjectValue $value
 */
class InputObjectValueGenerator extends CompositeValueGenerator
{
    /**
     * @return string
     */
    public function toString(): string
    {
        $values = Iter::map($this->value, fn($value, string $key): string =>
            $key . ': ' . $this->create($value)->toString()
        );

        return $this->body($values);
    }

    /**
     * @param iterable|string[] $values
     * @return string
     */
    private function body(iterable $values): string
    {
        $values = Iter::toArray($values, false);

        return $this->isMultiline() ? $this->formatMultiline($values) : $this->formatInline($values);
    }

    /**
     * @param array|string[] $values
     * @return string
     */
    protected function formatMultiline(array $values): string
    {
        return "{\n" .
            $this->lines($values, $this->depth() + 1) . "\n" .
            $this->line('}', $this->depth())
        ;
    }

    /**
     * @param array|string[] $values
     * @return array|string[]
     */
    protected function formatInline(array $values): string
    {
        return '{' . $this->lines($values, $this->depth()) . '}';
    }
}
