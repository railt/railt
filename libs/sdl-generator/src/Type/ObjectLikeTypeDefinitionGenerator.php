<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\SDL\Generator\Definition\FieldDefinitionGenerator;
use Railt\TypeSystem\Definition\Type\ObjectLikeType;

/**
 * @template TDefinition of ObjectLikeType
 *
 * @template-extends TypeDefinitionGenerator<TDefinition>
 */
abstract class ObjectLikeTypeDefinitionGenerator extends TypeDefinitionGenerator
{
    abstract protected function getTitle(): string;

    protected function getInterfaces(): string
    {
        if ($this->type->getNumberOfInterfaces() === 0) {
            return '';
        }

        $result = [];

        foreach ($this->type->getInterfaces() as $interface) {
            $result[] = $interface->getName();
        }

        return \vsprintf('implements %s', [
            \implode(' & ', $result),
        ]);
    }

    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfDirectives()) {
            $result[] = $this->getTitle();

            foreach ($this->type->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 1);
            }

            $result[] = '{';
        } else {
            $result[] = $this->getTitle() . ' {';
        }

        foreach ($this->type->getFields() as $field) {
            $formatted = new FieldDefinitionGenerator($field, $this->config);

            $result[] = $this->printer->prefixed(1, (string)$formatted);
        }

        $result[] = '}';

        return $this->printer->join($result);
    }
}
