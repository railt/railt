<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Value;

use Railt\GraphQL\AST\Common\Name;

/**
 * Class EnumValue
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class EnumValue extends Value
{
    /**
     * @var string
     */
    public $value;

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof Name) {
            $this->value = $value->value;

            return true;
        }

        return parent::each($value);
    }
}
