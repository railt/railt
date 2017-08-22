<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Reflection\Abstraction\ScalarTypeInterface;
use Railt\Adapters\Webonyx\Request;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Adapters\Webonyx\Builder\Common\HasDescription;
use Railt\Adapters\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class FieldBuilder
 * @package Railt\Adapters\Webonyx
 * @property-read FieldInterface $type
 */
class FieldBuilder extends Builder
{
    use HasDescription;

    /**
     * @return array
     * @throws \Railt\Exceptions\CompilerException
     * @throws \Railt\Exceptions\RuntimeException
     * @throws \LogicException
     */
    public function build(): array
    {
        return [
            'type'        => $this->makeType(),
            'description' => $this->getDescription(),
            'args'        => iterator_to_array($this->getFieldArguments())
        ];
    }

    /**
     * @return Type
     * @throws \LogicException
     */
    private function makeType(): Type
    {
        return $this->make($this->type->getType(), TypeBuilder::class);
    }

    /**
     * @return \Traversable
     * @throws \LogicException
     */
    private function getFieldArguments(): \Traversable
    {
        foreach ($this->type->getArguments() as $arg) {
            yield $arg->getName() => $this->make($arg, ArgumentBuilder::class);
        }
    }
}
