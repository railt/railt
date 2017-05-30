<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Creators;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Schema\Definitions\TypeDefinition;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinition;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;

/**
 * Class ArgumentDefinitionCreator
 * @package Serafim\Railgun\Schema\Creators
 */
class ArgumentDefinitionCreator implements CreatorInterface
{
    use InteractWithName;
    use ProvidesTypeDefinition;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed|null
     */
    private $defaultValue;

    /**
     * ArgumentDefinitionCreator constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->rename($this->type = $type);
    }

    /**
     * @param mixed $value
     * @return ArgumentDefinitionCreator|$this
     */
    public function default($value): ArgumentDefinitionCreator
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @return ArgumentDefinitionInterface
     * @throws \ReflectionException
     */
    public function build(): ArgumentDefinitionInterface
    {
        return (
            new ArgumentDefinition(
                new TypeDefinition($this->type, $this->isNullable, $this->isList),
                $this->defaultValue
            )
        )
            ->rename($this->getName())
            ->about($this->getDescription());
    }
}
