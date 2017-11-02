<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Base\Definitions\BaseEnum;
use Railt\Compiler\Reflection\Builder\Definitions\Enum\ValueBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;

/**
 * Class EnumBuilder
 */
class EnumBuilder extends BaseEnum implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * EnumBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \LogicException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $value = new ValueBuilder($ast, $this->getDocument(), $this);

            $this->values = $this->getValidator()->uniqueDefinitions($this->values, $value);

            return true;
        }

        return false;
    }
}
