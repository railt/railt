<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\Parser\Ast\RuleInterface;

/**
 * Class ObjectValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class ObjectValue extends Value
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
        if ($value instanceof RuleInterface) {
            [$name, $value] = $this->readObjectField($value);

            $this->value[$name] = $value;

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'object';
    }

    /**
     * @param RuleInterface $rule
     * @return array
     */
    private function readObjectField(RuleInterface $rule): array
    {
        $name = $rule->getChild(0)->value;

        return [$name, $rule->getChild(1)];
    }

    /**
     * @return string
     */
    protected function getRenderableValue(): string
    {
        $result = [];

        foreach ($this->value as $key => $value) {
            $result[] = $key . ': ' . $value;
        }

        return '{' . \implode(', ', $result) . '}';
    }

    /**
     * @return iterable
     */
    public function getValue(): iterable
    {
        return $this->value;
    }
}
