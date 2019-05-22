<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\Parser\Ast\LeafInterface;

/**
 * Class BooleanValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class BooleanValue extends Value
{
    /**
     * @var bool
     */
    public $value;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof LeafInterface) {
            $this->value = \strtolower($value->getValue()) === 'true';

            return true;
        }

        return parent::each($value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'bool';
    }

    /**
     * @return string
     */
    protected function getRenderableValue(): string
    {
        return $this->value ? 'true' : 'false';
    }

    /**
     * @return bool|mixed
     */
    public function getValue(): bool
    {
        return $this->value;
    }
}
