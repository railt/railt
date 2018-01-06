<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Invocations;

use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\Compiler\TreeNode;
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
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, TypeDefinition $parent)
    {
        $this->parent = $parent;
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Argument') {
            [$name, $value] = $this->parseArgumentValue($ast);

            $this->arguments[$name] = $this->parseValue($value, $this->getName());

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
        return $this->load($this->getName());
    }
}
