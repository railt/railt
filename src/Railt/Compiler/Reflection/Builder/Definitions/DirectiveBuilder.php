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
use Railt\Compiler\Exceptions\TypeConflictException;

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
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeConflictException
     * @throws \ReflectionException
     */
    public function compile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Target':
                /** @var TreeNode $child */
                foreach ($ast->getChild(0)->getChildren() as $child) {
                    $location          = $this->verifyLocation($child->getValueValue());
                    $this->locations[] = $location;
                }
        }

        return false;
    }

    /**
     * TODO Move verification into external class
     *
     * @param string $location
     * @return string
     * @throws TypeConflictException
     * @throws \ReflectionException
     */
    private function verifyLocation(string $location): string
    {
        $location = \strtoupper($location);

        if (! \in_array($location, $this->getAllAllowedLocations(), true)) {
            $error = 'Invalid directive location "%s"';
            throw new TypeConflictException(\sprintf($error, $location));
        }

        return $location;
    }
}
