<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Proxy;

use Railt\GraphQL\AST\TypeHint\ListType;
use Railt\GraphQL\AST\TypeHint\NonNullType;
use Railt\GraphQL\AST\TypeHint\Type;
use Railt\GraphQL\AST\TypeHint\TypeHintInterface;

/**
 * Trait TypeHintProxyTrait
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
trait TypeHintProxyTrait
{
    /**
     * @var TypeHintInterface|static
     */
    public $of;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function eachTypeHintProxyTrait($value): bool
    {
        $hint = [ListType::class, NonNullType::class, Type::class];

        if (\in_array(\get_class($value), $hint, true)) {
            $this->of = $value;

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->of->getType();
    }

    /**
     * @return TypeHintInterface
     */
    public function of(): TypeHintInterface
    {
        return $this->of;
    }
}
