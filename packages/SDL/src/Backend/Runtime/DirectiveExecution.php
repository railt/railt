<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Runtime;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * Class DirectiveExecution
 */
class DirectiveExecution extends Execution implements DirectiveExecutionInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * DirectiveExecution constructor.
     *
     * @param string $name
     * @param DefinitionInterface $context
     * @param iterable $arguments
     */
    public function __construct(string $name, DefinitionInterface $context, iterable $arguments = [])
    {
        $this->name = $name;

        parent::__construct($context, $arguments);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }
}
