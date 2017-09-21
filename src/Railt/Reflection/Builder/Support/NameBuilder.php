<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Trait NameBuilder
 * @mixin Nameable
 */
trait NameBuilder
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @param TreeNode $ast
     * @return void
     * @throws BuildingException
     */
    private function bootNameBuilder(TreeNode $ast): void
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            switch ($child->getId()) {
                case '#Name':
                    $this->name = $this->compileName($child);
                    break;

                case '#Description':
                    $this->description = $this->compileDescription($child, true);
                    break;
            }
        }

        $this->verifyNameConsistency($ast, '#Name');
    }


    /**
     * @param TreeNode $root
     * @param string $name
     * @return void
     * @throws BuildingException
     */
    private function verifyNameConsistency(TreeNode $root, string $name): void
    {
        if ($this->name === null) {
            $error  = 'The AST must contain the Node named %s for the correct %s construction. ' .
                'The transmitted AST contains the following structure: ' . \PHP_EOL;
            $error .=  $this->getCompiler()->dump($root);

            throw new BuildingException(\sprintf($error, $name, $this->getTypeName()));
        }
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    private function compileName(TreeNode $ast): string
    {
        return $ast->getChild(0)->getValueValue();
    }

    /**
     * @param TreeNode $ast
     * @param bool $escapeComment
     * @return string
     * @internal param string $description
     */
    private function compileDescription(TreeNode $ast, bool $escapeComment = true): string
    {
        $description = $ast->getChild(0)->getValueValue();

        return $escapeComment
            ? \preg_replace('/^#?\h+(.*?)$/imsu', '$1', $description)
            : $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
