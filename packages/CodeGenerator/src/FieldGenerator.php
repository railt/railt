<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator;

use GraphQL\Contracts\TypeSystem\FieldInterface;

/**
 * @property-read FieldInterface $type
 */
class FieldGenerator extends DefinitionGenerator
{
    /**
     * @return string
     */
    public function toString(): string
    {
        $result = $this->type->getName() . $this->renderArguments() . ': ' . $this->type->getType();

        return $this->renderDescription($this->type) .
            $this->line($result, $this->depth())
        ;
    }

    /**
     * @return string
     */
    protected function renderArguments(): string
    {
        $result = [];

        foreach ($this->type->getArguments() as $argument) {
            $generator = new ArgumentGenerator($argument, $this->config([
                ArgumentGenerator::CONFIG_DEPTH     => $this->depth() + 1,
                ArgumentGenerator::CONFIG_MULTILINE => true,
            ]));

            $result[] = $generator->toString();
        }

        if ($result === []) {
            return '';
        }

        $delimiter = $this->isMultiline() ? "\n" : '';

        return '(' . $delimiter . $this->lines($result, 0) . $delimiter . $this->line(')', $this->depth());
    }
}
