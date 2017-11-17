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
use Railt\Compiler\Reflection\Builder\Dependent\Field\FieldsBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Definitions\BaseObject;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends BaseObject implements Compilable
{
    use Compiler;
    use FieldsBuilder;
    use DirectivesBuilder;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('type');
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Implements') {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $name = $child->getChild(0)->getValueValue();

                $interface = $this->load($name);

                $this->interfaces = $this->unique($this->interfaces, $interface);
            }

            return true;
        }

        return false;
    }
}
