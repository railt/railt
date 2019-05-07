<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Definitions;

use Railt\SDL\Base\Dependent\Field\BaseFieldsContainer;
use Railt\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\SDL\Contracts\Definitions\InterfaceDefinition;
use Railt\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\SDL\Contracts\Type;

/**
 * Class BaseObject
 */
abstract class BaseObject extends BaseTypeDefinition implements ObjectDefinition
{
    use BaseDirectivesContainer;
    use BaseFieldsContainer;

    /**
     * Object type name
     */
    protected const TYPE_NAME = Type::OBJECT;

    /**
     * @var array|InterfaceDefinition[]
     */
    protected $interfaces = [];

    /**
     * @return iterable|InterfaceDefinition[]
     */
    public function getInterfaces(): iterable
    {
        return \array_values($this->interfaces);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return \array_key_exists($name, $this->interfaces);
    }

    /**
     * @param string $name
     * @return null|InterfaceDefinition
     */
    public function getInterface(string $name): ?InterfaceDefinition
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int
    {
        return \count($this->interfaces);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // self class
            'interfaces',

            // trait HasFields
            'fields',

            // trait HasDirectives
            'directives',
        ]);
    }
}
