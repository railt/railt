<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Definition;

use Railt\SDL\Generator\Type\DefinitionGenerator;
use Railt\TypeSystem\Definition\SchemaDefinition;

/**
 * @template-extends DefinitionGenerator<SchemaDefinition>
 */
final class SchemaDefinitionGenerator extends DefinitionGenerator
{
    public function __toString(): string
    {
        $result = [];

        if ($description = $this->type->getDescription()) {
            $result[] = $this->description($description);
        }

        if ($this->type->getNumberOfDirectives()) {
            $result[] = 'schema';

            foreach ($this->type->getDirectives() as $directive) {
                $result[] = $this->directive($directive, 1);
            }

            $result[] = '{';
        } else {
            $result[] = 'schema {';
        }

        if ($query = $this->type->getQueryType()) {
            $result[] = $this->printer->prefixed(1, 'query: %s', [
                $query->getName(),
            ]);
        }

        if ($mutation = $this->type->getMutationType()) {
            $result[] = $this->printer->prefixed(1, 'mutation: %s', [
                $mutation->getName(),
            ]);
        }

        if ($subscription = $this->type->getSubscriptionType()) {
            $result[] = $this->printer->prefixed(1, 'subscription: %s', [
                $subscription->getName(),
            ]);
        }

        $result[] = '}';

        return $this->printer->join($result);
    }
}
