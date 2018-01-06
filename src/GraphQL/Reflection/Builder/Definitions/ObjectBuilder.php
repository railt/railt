<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Definitions;

use Railt\GraphQL\Reflection\Builder\Dependent\Field\FieldsBuilder;
use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\Compiler\TreeNode;
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
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('type');
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
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
