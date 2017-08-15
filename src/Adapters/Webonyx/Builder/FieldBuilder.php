<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Railgun\Webonyx\Request;
use Railgun\Reflection\Abstraction\FieldInterface;
use Railgun\Webonyx\Builder\Common\HasDescription;
use Railgun\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class FieldBuilder
 * @package Railgun\Webonyx
 * @property-read FieldInterface $type
 */
class FieldBuilder extends Builder
{
    use HasDescription;

    /**
     * @return array
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\RuntimeException
     * @throws \LogicException
     */
    public function build(): array
    {
        return [
            'type'        => $this->makeType(),
            'description' => $this->getDescription(),
            'args'        => iterator_to_array($this->getFieldArguments()),
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
