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
use Serafim\Railgun\Types\Schemas\TypeDefinition;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;
use Serafim\Railgun\Contracts\Partials\ArgumentTypeInterface;

/**
 * Class ArgumentCreator
 * @package Serafim\Railgun\Types\Creators
 */
class ArgumentCreator extends TypeCreator implements ArgumentTypeInterface
{
    use InteractWithName;

    /**
     * @var bool
     */
    private $hasDefaultValue = false;

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * ArgumentCreator constructor.
     * @param string $type
     * @param null|string $name
     */
    public function __construct(string $type, ?string $name = null)
    {
        $this->name = $name;

        parent::__construct($type, null);
    }


    /**
     * @param $value
     * @return ArgumentCreator
     */
    public function default($value): ArgumentCreator
    {
        $this->hasDefaultValue = true;
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @return ArgumentCreator
     */
    public function withoutDefaultValue(): ArgumentCreator
    {
        $this->hasDefaultValue = false;
        $this->defaultValue = null;

        return $this;
    }

    /**
     * @param TypeDefinition $schema
     * @return TypeDefinitionInterface
     */
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
