<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Runtime;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * Class DirectiveExecution
 */
class DirectiveExecution extends Execution implements DirectiveExecutionInterface
{
    /**
     * @var DirectiveInterface
     */
    private DirectiveInterface $directive;

    /**
     * DirectiveExecution constructor.
     *
     * @param DirectiveInterface $directive
     * @param DefinitionInterface $context
     * @param iterable $arguments
     */
    public function __construct(DirectiveInterface $directive, DefinitionInterface $context, iterable $arguments = [])
    {
        $this->directive = $directive;

        parent::__construct($context, $arguments);
    }

    /**
     * @return DirectiveInterface
     */
    public function getDirective(): DirectiveInterface
    {
        return $this->directive;
    }
}
