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
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class FieldBuilder
 */
class FieldBuilder extends AbstractField
{
    use TypeIndicationBuilder;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var FieldDefinition
     */
    private $type;

    /**
     * FieldBuilder constructor.
     * @param AdapterInterface $adapter
     * @param FieldDefinition $type
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    public function __construct(AdapterInterface $adapter, FieldDefinition $type)
    {
        $this->adapter = $adapter;
        $this->type    = $type;

        parent::__construct();
    }

    /**
     * @param FieldConfig $config
     * @return void
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    public function build(FieldConfig $config): void
    {
        foreach ($this->type->getArguments() as $argument) {
            $config->addArgument($argument->getName(), [
                'name' => $argument->getName(),
                'type' => $this->typeOf($this->adapter, $argument),
            ]);
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
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->type->isDeprecated();
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return $this->type->getDeprecationReason();
    }

    /**
     * @return TypeInterface
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    public function getType(): TypeInterface
    {
        return $this->typeOf($this->adapter, $this->type);
    }
}
