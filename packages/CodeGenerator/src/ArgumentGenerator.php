<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * @property-read ArgumentInterface $type
 */
class ArgumentGenerator extends DefinitionGenerator
{
    /**
     * ArgumentGenerator constructor.
     *
     * @param DefinitionInterface|ArgumentInterface $type
     * @param array $config
     */
    public function __construct(DefinitionInterface $type, $config = [])
    {
        \assert($type instanceof ArgumentInterface);

        parent::__construct($type, $config);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $description  = \rtrim($this->renderDescription($this->type), "\n");
        $description .= $this->isMultiline() ? "\n" : ' ';

        return
            $description .
                $this->line($this->renderArgument(), $this->depth()) .
            $this->renderDefaultValue()
        ;
    }

    /**
     * @return string
     */
    private function renderArgument(): string
    {
        return $this->type->getName() . ': ' . $this->type->getType();
    }

    /**
     * @return string
     */
    private function renderDefaultValue(): string
    {
        if ($this->type->hasDefaultValue()) {
            return ' = ' . $this
                    ->value($this->type->getDefaultValue(), $this->config->all())
                    ->toString()
            ;
        }

        return '';
    }
}
