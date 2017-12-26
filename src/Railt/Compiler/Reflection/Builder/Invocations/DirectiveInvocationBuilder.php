<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Invocations;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Invocations\BaseDirectiveInvocation;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class DirectiveInvocationBuilder
 */
class DirectiveInvocationBuilder extends BaseDirectiveInvocation implements Compilable
{
    use Compiler;

    /**
     * DirectiveInvocationBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param TypeDefinition $parent
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Argument') {
            [$name, $value] = $this->parseArgumentValue($ast);

            $this->arguments[$name] = $this->parseValue($value, $this->getTypeDefinition());

            return true;
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return array
     */
    private function parseArgumentValue(TreeNode $ast): array
    {
        [$key, $value] = [null, null];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Name') {
                $key = $child->getChild(0)->getValueValue();
                continue;
            }

            if ($child->getId() === '#Value') {
                $value = $child->getChild(0);
                continue;
            }
        }

        return [$key, $value];
    }

    /**
     * @return null|TypeDefinition
     */
    public function getTypeDefinition(): ?TypeDefinition
    {
        try {
            return $this->load($this->getName());
        } catch (TypeNotFoundException $error) {
            return null;
        }
    }
}
