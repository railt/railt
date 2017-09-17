<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\ArgumentInterface;
use Railt\Reflection\Contracts\InputTypeInterface;
use Railt\Reflection\Contracts\ObjectTypeInterface;
use Railt\Reflection\Contracts\ScalarTypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Reflection\Common\Arguments;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class InputDefinition
 *
 * @package Railt\Reflection\Reflection
 */
class InputDefinition extends Definition implements
    InputTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Arguments;
    use Directives;
    use LinkingStage;
    use HasDescription;

    /**
     * @var string
     */
    protected $astHasArguments = '#Field';

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function complete(Document $document, TreeNode $ast): void
    {
        foreach ($this->getArguments() as $field) {
            $this->checkFieldType($field);
        }
    }

    /**
     * Make sure that the field matches the type you want.
     *
     * @param ArgumentInterface $field
     * @throws TypeConflictException
     */
    private function checkFieldType(ArgumentInterface $field): void
    {
        $relation = $field->getType()->getRelationDefinition();

        $isValidType =
            $relation instanceof InputTypeInterface ||
            $relation instanceof ScalarTypeInterface;

        if (! $isValidType) {
            $error = 'Input field %s.%s must be type of Input or Scalar but %s (%s) given.';
            $error = sprintf($error, $field->getParent()->getName(), $field->getName(),
                $relation->getTypeName(), $relation->getName());
            throw new TypeConflictException($error);
        }
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Input';
    }
}
