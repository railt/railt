<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions;

use Railt\Parser\Ast\NodeInterface;
use Railt\SDL\Base\Definitions\BaseEnum;
use Railt\SDL\Reflection\Builder\Definitions\Enum\ValueBuilder;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

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
     * @throws \Railt\SDL\Exceptions\TypeConflictException
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
        if ($ast->is('Value')) {
            $value = new ValueBuilder($ast, $this->getDocument(), $this);

            $this->values = $this->unique($this->values, $value);

            return true;
        }

        return false;
    }
}
