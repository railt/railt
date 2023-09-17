<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Evaluate;

use Railt\SDL\Compiler\Command\CommandInterface;
use Railt\SDL\Compiler\Context;
use Railt\TypeSystem\Definition\SchemaDefinition;
use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
final class GenerateSchemaCommand implements CommandInterface
{
    public function __construct(
        private readonly Context $ctx,
    ) {}

    public function exec(): void
    {
        $config = $this->ctx->config->generateSchema;

        // In case of schema SHOULD NOT be generated
        if ($config === null) {
            return;
        }

        // In case of schema already has been generated
        if ($this->ctx->types->hasSchemaDefinition()) {
            return;
        }

        $schema = new SchemaDefinition();

        if ($query = $this->fetchSchemaType($config->queryTypeName)) {
            $schema->setQueryType($query);
        }

        if ($mutation = $this->fetchSchemaType($config->mutationTypeName)) {
            $schema->setMutationType($mutation);
        }

        if ($subscription = $this->fetchSchemaType($config->subscriptionTypeName)) {
            $schema->setSubscriptionType($subscription);
        }

        $this->ctx->types->setSchemaDefinition($schema);
    }

    /**
     * @param non-empty-string|null $name
     *
     * @psalm-suppress TypeDoesNotContainType : Additional "non-empty-string" assertion.
     */
    private function fetchSchemaType(?string $name): ?ObjectType
    {
        if ($name === null || $name === '') {
            return null;
        }

        $type = $this->ctx->types->findTypeDefinition($name);

        if ($type instanceof ObjectType) {
            return $type;
        }

        return null;
    }
}
