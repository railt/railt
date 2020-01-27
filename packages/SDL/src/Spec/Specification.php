<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec;

use Phplrt\Source\File;
use Phplrt\Visitor\Visitor;
use Railt\SDL\CompilerInterface;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Spec\Constraint\Constraint;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;
use Railt\SDL\Spec\Constraint\RepeatableDirectives;
use Railt\SDL\Spec\Constraint\TypeSystemExtensions;

/**
 * Class Specification
 */
abstract class Specification extends Visitor implements SpecificationInterface
{
    /**
     * @var string
     */
    private const RESOURCES_PATHNAME = __DIR__ . '/../../resources/stdlib/%s.graphql';

    /**
     * @var string[]|Constraint[]
     */
    protected const STANDARD_CONSTRAINTS = [
        RepeatableDirectives::class,
        TypeSystemExtensions::class,
    ];

    /**
     * List of language version constraints.
     * That is, additional restrictions which should be added to
     * standard constraints.
     *
     * @var array|Constraint[]
     */
    protected array $constraints = [];

    /**
     * List of the language abilities.
     * That is, features that are excluded from
     * standard constraints.
     *
     * @var array|Constraint[]
     */
    protected array $abilities = [];

    /**
     * List of the language types.
     *
     * @var array|string[]
     */
    protected array $types = [];

    /**
     * Specification constructor.
     */
    public function __construct()
    {
        $this->bootConstraints();
    }

    /**
     * @return void
     */
    private function bootConstraints(): void
    {
        foreach (static::STANDARD_CONSTRAINTS as $constraint) {
            if (! \in_array($constraint, $this->abilities, true)) {
                $this->constraints[] = $constraint;
            }
        }

        $this->constraints = \array_unique($this->constraints);
    }

    /**
     * @param CompilerInterface $compiler
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     */
    public function load(CompilerInterface $compiler): void
    {
        $this->includeMany($compiler, $this->types);
    }

    /**
     * @param CompilerInterface $compiler
     * @param array $names
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     */
    protected function includeMany(CompilerInterface $compiler, array $names): void
    {
        foreach ($names as $name) {
            $this->include($compiler, $name);
        }
    }

    /**
     * @param CompilerInterface $compiler
     * @param string $name
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     */
    protected function include(CompilerInterface $compiler, string $name): void
    {
        $compiler->preload($this->fromResources($name));
    }

    /**
     * @param string $name
     * @return FileInterface
     * @throws NotFoundException
     * @throws NotReadableException
     */
    protected function fromResources(string $name): FileInterface
    {
        return File::fromPathname(\sprintf(self::RESOURCES_PATHNAME, $name));
    }

    /**
     * @param NodeInterface $node
     * @return void
     */
    public function enter(NodeInterface $node): void
    {
        foreach ($this->constraints as $constraint) {
            $constraint::assert($node, $this);
        }
    }
}
