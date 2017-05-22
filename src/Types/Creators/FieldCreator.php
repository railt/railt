<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Creators;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;
use Serafim\Railgun\Types\Schemas\TypeDefinition;

/**
 * Class FieldRegistrar
 * @package Serafim\Railgun\Types\Creators
 *
 * @method FieldCreator many()
 * @method FieldCreator single()
 * @method FieldCreator nullable()
 * @method FieldCreator notNull()
 */
class FieldCreator extends TypeCreator implements FieldTypeInterface
{
    use InteractWithName;

    /**
     * @var \Closure|null
     */
    private $resolver;

    /**
     * @var string|null
     */
    private $deprecationReason;

    /**
     * @param TypeDefinition $definition
     * @return TypeDefinitionInterface
     */
    public function getType(TypeDefinition $definition): TypeDefinitionInterface
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isResolvable(): bool
    {
        return $this->resolver !== null;
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
     * @param string $reason
     * @return FieldCreator
     */
    public function deprecate(?string $reason = null): FieldCreator
    {
        $this->deprecationReason = $reason ?? ($this->getName() . ' is deprecated');

        return $this;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return mixed
     */
    public function resolve($value, array $arguments = [])
    {
        $result = ($this->resolver)($value, $arguments);

        if ($result instanceof \Traversable) {
            return iterator_to_array($result);
        }

        return $result;
    }

    /**
     * @param \Closure $then
     * @return FieldCreator
     */
    public function then(\Closure $then): FieldCreator
    {
        $this->resolver = $then;

        return $this;
    }
}
