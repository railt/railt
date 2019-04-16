<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Builder\Definitions;

use Railt\Component\Parser\Ast\NodeInterface;
use Railt\Component\Parser\Ast\RuleInterface;
use Railt\Component\SDL\Base\Definitions\BaseDirective;
use Railt\Component\SDL\Reflection\Builder\Dependent\Argument\ArgumentsBuilder;
use Railt\Component\SDL\Reflection\Builder\DocumentBuilder;
use Railt\Component\SDL\Reflection\Builder\Process\Compilable;
use Railt\Component\SDL\Reflection\Builder\Process\Compiler;
use Railt\Component\SDL\Reflection\Validation\Uniqueness;

/**
 * Class DirectiveBuilder
 */
class DirectiveBuilder extends BaseDirective implements Compilable
{
    use Compiler;
    use ArgumentsBuilder;

    /**
     * DirectiveBuilder constructor.
     *
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     * @throws \OutOfBoundsException
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('directive');
    }

    /**
     * @param NodeInterface|RuleInterface $ast
     * @return bool
     * @throws \OutOfBoundsException
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        switch ($ast->getName()) {
            case 'Target':
                $validator = $this->getValidator(Uniqueness::class);

                foreach ($ast->getChild(0)->getChildren() as $child) {
                    $location = $child->getValue();

                    $validator->validate($this->locations, $child->getValue(), static::LOCATION_TYPE_NAME);

                    $this->locations[] = $location;
                }
        }

        return false;
    }
}
