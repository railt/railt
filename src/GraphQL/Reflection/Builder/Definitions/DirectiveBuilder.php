<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Definitions;

use Railt\Compiler\TreeNode;
use Railt\GraphQL\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
use Railt\GraphQL\Reflection\Validation\Uniqueness;
use Railt\Reflection\Base\Definitions\BaseDirective;

/**
 * Class DirectiveBuilder
 */
class DirectiveBuilder extends BaseDirective implements Compilable
{
    use Compiler;
    use ArgumentsBuilder;

    /**
     * DirectiveBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('directive');
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \OutOfBoundsException
     * @throws \Railt\GraphQL\Exceptions\TypeRedefinitionException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Target':
                $validator = $this->getValidator(Uniqueness::class);

                /** @var TreeNode $child */
                foreach ($ast->getChild(0)->getChildren() as $child) {
                    $location = $child->getValueValue();

                    $validator->validate($this->locations, $location, static::LOCATION_TYPE_NAME);

                    $this->locations[] = $location;
                }
        }

        return false;
    }
}
