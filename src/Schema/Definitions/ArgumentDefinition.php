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
 * Class ArgumentDefinition
 * @package Serafim\Railgun\Schema\Definitions
 */
class ArgumentDefinition implements ArgumentDefinitionInterface
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
     * @var mixed|null
     */
    private $defaultValue;

    /**
     * ArgumentDefinition constructor.
     * @param TypeDefinitionInterface $type
     * @param mixed|null $defaultValue
     */
    public function __construct(TypeDefinitionInterface $type, $defaultValue = null)
    {
        $this->type = $type;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    final protected function getDescriptionSuffix(): string
    {
        return 'argument definition';
    }

    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return TypeDefinitionInterface
     */
    public function getTypeDefinition(): TypeDefinitionInterface
    {
        return $this->type;
    }
}
