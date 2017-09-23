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
use Railt\Reflection\Builder\Runtime\TypeBuilder;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Exceptions\BuildingException;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder implements SchemaType
{
    use TypeBuilder;

    private const AST_ID_QUERY = '#Query';
    private const AST_ID_MUTATION = '#Mutation';
    private const AST_ID_SUBSCRIPTION = '#Subscription';
    private const AST_ID_FIELD_NAME = '#Type';

    /**
     * @var ObjectType
     */
    private $query;

    /**
     * @var ObjectType|null
     */
    private $mutation;

    /**
     * @var ObjectType|null
     */
    private $subscription;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootTypeBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function compile(TreeNode $ast): bool
    {
        $type = null;

        switch ($ast->getId()) {
            case self::AST_ID_QUERY:
                $this->query = $type = $this->fetchType($ast);
                break;

            case self::AST_ID_MUTATION:
                $this->mutation = $type = $this->fetchType($ast);
                break;

            case self::AST_ID_SUBSCRIPTION:
                $this->subscription = $type = $this->fetchType($ast);
                break;
        }

        if ($type === null) {
            $this->throwInvalidAstNodeError($ast);
        }

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return ObjectType|TypeInterface
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    private function fetchType(TreeNode $ast): ObjectType
    {
        $field = $ast->getChild(0);

        if ($field->getId() !== self::AST_ID_FIELD_NAME) {
            $this->throwInvalidAstNodeError($field);
        }

        $name = $field->getChild(0)->getValueValue();

        if (! \is_string($name)) {
            $this->throwInvalidAstNodeError($field);
        }

        return $this->getCompiler()->get($name);
    }

    /**
     * @return ObjectType
     * @throws BuildingException
     */
    public function getQuery(): ObjectType
    {
        $this->compileIfNotCompiled();

        $this->verifyQuery($this->getAst());

        return $this->query;
    }

    /**
     * @param TreeNode $root
     * @return void
     * @throws BuildingException
     */
    private function verifyQuery(TreeNode $root): void
    {
        if ($this->query === null) {
            $error = 'The %s must contain a query field, but an error occurred during ' .
                'the compiling and there is no required field.' . \PHP_EOL .
                'The transmitted AST contains the following structure: ' . \PHP_EOL .
                $this->getCompiler()->dump($root);

            throw new BuildingException(\sprintf($error, $this->getTypeName()));
        }
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Schema';
    }

    /**
     * @return null|ObjectType
     */
    public function getMutation(): ?ObjectType
    {
        return $this->compiled()->mutation;
    }

    /**
     * @return bool
     */
    public function hasMutation(): bool
    {
        return $this->compiled()->mutation !== null;
    }

    /**
     * @return null|ObjectType
     */
    public function getSubscription(): ?ObjectType
    {
        return $this->compiled()->subscription;
    }

    /**
     * @return bool
     */
    public function hasSubscription(): bool
    {
        return $this->compiled()->subscription !== null;
    }
}
