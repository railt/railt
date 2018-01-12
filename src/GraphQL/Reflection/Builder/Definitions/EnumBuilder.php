<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Definitions;

use Railt\Compiler\Ast\NodeInterface;
use Railt\GraphQL\Reflection\Builder\Definitions\Enum\ValueBuilder;
use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Definitions\BaseEnum;

/**
 * Class EnumBuilder
 */
class EnumBuilder extends BaseEnum implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * EnumBuilder constructor.
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('enum');
    }

    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws \LogicException
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        if ($ast->is('#Value')) {
            $value = new ValueBuilder($ast, $this->getDocument(), $this);

            $this->values = $this->unique($this->values, $value);

            return true;
        }

        return false;
    }
}
