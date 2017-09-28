<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseExtend;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class ExtendBuilder
 */
class ExtendBuilder extends BaseExtend implements Compilable
{
    use Builder;

    /**
     * ExtendBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        $type = DocumentBuilder::AST_TYPE_MAPPING[$ast->getId()] ?? null;

        if ($type !== null) {
            $this->applyExtender(new $type($ast, $this->getDocument()));
        }

        return false;
    }

    /**
     * @param Nameable|TypeInterface|Compilable $instance
     * @return TypeInterface
     */
    private function applyExtender(TypeInterface $instance): TypeInterface
    {
        $instance->compileIfNotCompiled();

        /** @var TypeInterface $original */
        $original = $this->getCompiler()->get($instance->getName());

        return $this->extend($original, $instance);
    }

    /**
     * @param TypeInterface $original
     * @param TypeInterface $extend
     * @return TypeInterface
     */
    private function extend(TypeInterface $original, TypeInterface $extend): TypeInterface
    {
        throw new \LogicException('TODO');

        return $original;
    }
}
