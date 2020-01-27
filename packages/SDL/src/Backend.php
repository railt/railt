<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\Linker\LinkerInterface;
use Railt\SDL\Backend\Linker\LinkerVisitor;
use Railt\SDL\Backend\TypeBuilderVisitor;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Spec\Executor;
use Railt\SDL\Spec\SpecificationInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;
use Railt\TypeSystem\Schema;

/**
 * Class Backend
 */
class Backend
{
    /**
     * @var Executor
     */
    private Executor $spec;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var LinkerInterface
     */
    private LinkerInterface $linker;

    /**
     * Backend constructor.
     *
     * @param SpecificationInterface $spec
     * @param Context $context
     * @param LinkerInterface $linker
     */
    public function __construct(SpecificationInterface $spec, Context $context, LinkerInterface $linker)
    {
        $this->spec = new Executor($spec);
        $this->context = $context;
        $this->linker = $linker;
    }

    /**
     * @param iterable|Node[] $ast
     * @param array $variables
     * @return SchemaInterface
     * @throws \Throwable
     */
    public function run(iterable $ast, array $variables = []): SchemaInterface
    {
        $schema = new Schema();

        $ast = $this->adoptSpecification($ast);
        $ast = $this->buildTypes($ast, $schema);

        $this->linkTypes($ast);

        return $this->moveData($this->context, $schema, $variables);
    }

    /**
     * @param iterable|Node[] $ast
     * @return iterable|Node[]
     */
    private function adoptSpecification(iterable $ast): iterable
    {
        return $this->spec->execute($ast);
    }

    /**
     * @param iterable $ast
     * @param Schema $schema
     * @return iterable
     */
    private function buildTypes(iterable $ast, Schema $schema): iterable
    {
        $traverser = new Traverser([
            new TypeBuilderVisitor($this->context, $schema),
        ]);

        return $traverser->traverse($ast);
    }

    /**
     * @param iterable $ast
     * @return iterable
     */
    private function linkTypes(iterable $ast): iterable
    {
        $traverser = new Traverser([
            new LinkerVisitor($this->context, $this->linker),
        ]);

        return $traverser->traverse($ast);
    }

    /**
     * @param Context $context
     * @param Schema $schema
     * @param array $variables
     * @return Schema
     * @throws TypeUniquenessException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    private function moveData(Context $context, Schema $schema, array $variables): Schema
    {
        foreach ($this->context->getTypes() as $type) {
            $schema->addType($type->resolve($variables));
        }

        foreach ($this->context->getDirectives() as $type) {
            $schema->addDirective($type->resolve($variables));
        }

        $schema->setQueryType($context->getQuery());
        $schema->setMutationType($context->getMutation());
        $schema->setSubscriptionType($context->getSubscription());

        return $schema;
    }
}
