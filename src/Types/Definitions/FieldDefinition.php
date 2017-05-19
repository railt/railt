<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Definitions;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;
use Serafim\Railgun\Contracts\Registrars\FieldRegistrarInterface;
use Serafim\Railgun\Contracts\Definitions\TypeDefinitionInterface;

/**
 * Class FieldDefinition
 * @package Serafim\Railgun\Types\Definitions
 *
 * @method FieldDefinition many()
 * @method FieldDefinition single()
 * @method FieldDefinition nullable()
 * @method FieldDefinition notNull()
 */
class FieldDefinition extends TypeDefinition implements FieldTypeInterface, FieldRegistrarInterface
{
    use InteractWithName;

    /**
     * @var \Closure|null
     */
    private $resolver;

    /**
     * @return TypeDefinitionInterface
     */
    public function getType(): TypeDefinitionInterface
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
     * @return FieldRegistrarInterface|FieldDefinition
     */
    public function then(\Closure $then): FieldRegistrarInterface
    {
        $this->resolver = $then;

        return $this;
    }
}
