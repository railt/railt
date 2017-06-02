<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Definitions;

use Serafim\Railgun\Support\InteractWithName;

/**
 * Class FieldDefinition
 * @package Serafim\Railgun\Schema\Definitions
 */
class FieldDefinition implements FieldDefinitionInterface
{
    use InteractWithName {
        rename as private;
        about as private;
    }

    /**
     * @var TypeDefinitionInterface
     */
    private $type;

    /**
     * @var iterable
     */
    private $arguments;

    /**
     * @var null|string
     */
    private $deprecationReason;

    /**
     * @var \Closure|null
     */
    private $resolver;

    /**
     * FieldDefinition constructor.
     * @param TypeDefinitionInterface $type
     * @param iterable $args
     * @param null|string $deprecation
     * @param \Closure|null $resolver
     */
    public function __construct(
        TypeDefinitionInterface $type,
        iterable $args,
        ?\Closure $resolver,
        ?string $deprecation
    )
    {
        $this->type = $type;
        $this->resolver = $resolver;
        $this->deprecationReason = $deprecation;
        $this->arguments = $args instanceof \Traversable ? iterator_to_array($args) : $args;
    }

    /**
     * @return string
     */
    final protected function getDescriptionSuffix(): string
    {
        return 'field definition';
    }

    /**
     * @return iterable|ArgumentDefinitionInterface[]
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    /**
     * @return bool
     */
    public function isResolvable(): bool
    {
        return $this->resolver !== null;
    }

    /**
     * @param array $arguments
     * @return mixed
     */
    public function resolve(array $arguments = [])
    {
        return ($this->resolver ?? function() {})($arguments);
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecationReason !== null;
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return (string)$this->deprecationReason;
    }

    /**
     * @return TypeDefinitionInterface
     */
    public function getTypeDefinition(): TypeDefinitionInterface
    {
        return $this->type;
    }
}
