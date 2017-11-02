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
use Railt\Compiler\Reflection\Base\Definitions\BaseDirective;
use Railt\Compiler\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;

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
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Target':
                /** @var TreeNode $child */
                foreach ($ast->getChild(0)->getChildren() as $child) {
                    $location = $child->getValueValue();

                    $this->locations = $this->getValidator()
                        ->uniqueValues($this->locations, $location, static::LOCATION_TYPE_NAME);
                }
        }

        return false;
    }
}
