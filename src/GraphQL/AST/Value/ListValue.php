<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

/**
 * Class ListValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class ListValue extends Value
{
    /**
     * @var iterable|ValueInterface[]
     */
    public $value = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof ValueInterface) {
            $this->value[] = $value;

            return true;
        }

        dump($value);

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'list';
    }

    /**
     * @return string
     */
    public function getRenderableValue(): string
    {
        $result = [];

        foreach ($this->value as $value) {
            $result[] = $value;
        }

        return '[' . \implode(', ', $result) . ']';
    }

    /**
     * @return iterable
     */
    public function getValue(): iterable
    {
        return $this->value;
    }
}
