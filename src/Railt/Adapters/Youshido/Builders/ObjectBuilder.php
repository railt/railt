<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Youshido\Builders;

use Railt\Adapters\AdapterInterface;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends AbstractObjectType
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var ObjectDefinition
     */
    private $type;

    /**
     * ObjectBuilder constructor.
     * @param AdapterInterface $adapter
     * @param ObjectDefinition $type
     */
    public function __construct(AdapterInterface $adapter, ObjectDefinition $type)
    {
        $this->adapter = $adapter;
        $this->type = $type;

        parent::__construct();
    }

    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     * @return void
     * @throws \LogicException
     */
    public function build($config): void
    {
        foreach ($this->type->getFields() as $field) {
            $config->addField($this->adapter->get($field));
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->type->getName();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->type->getDescription();
    }

    /**
     * @return array
     */
    public function getInterfaces(): array
    {
        $result = [];

        foreach ($this->type->getInterfaces() as $interface) {
            $result[] = $this->adapter->get($interface);
        }

        return $result;
    }
}
