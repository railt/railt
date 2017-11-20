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
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Standard\Scalars\BooleanType;
use Railt\Reflection\Standard\Scalars\DateTimeType;
use Railt\Reflection\Standard\Scalars\FloatType;
use Railt\Reflection\Standard\Scalars\IDType;
use Railt\Reflection\Standard\Scalars\IntType;
use Railt\Reflection\Standard\Scalars\StringType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar;

/**
 * Trait TypeIndicationBuilder
 */
trait TypeIndicationBuilder
{
    /**
     * @var array
     */
    private $predefined = [
        BooleanType::class  => Scalar\BooleanType::class,
        DateTimeType::class => Scalar\DateTimeType::class,
        FloatType::class    => Scalar\FloatType::class,
        IDType::class       => Scalar\IdType::class,
        IntType::class      => Scalar\IntType::class,
        StringType::class   => Scalar\StringType::class,
    ];

    /**
     * @param AdapterInterface $adapter
     * @param AllowsTypeIndication $type
     * @return mixed|ListType|NonNullType
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    protected function typeOf(AdapterInterface $adapter, AllowsTypeIndication $type)
    {
        $result = $this->loadType($adapter, $type);

        if ($type->isListOfNonNulls()) {
            $result = new ListType(new NonNullType($result));
        } elseif ($type->isList()) {
            $result = new ListType($result);
        }

        if ($type->isNonNull()) {
            $result = new NonNullType($result);
        }

        return $result;
    }

    /**
     * @param AdapterInterface $adapter
     * @param AllowsTypeIndication $type
     * @return mixed
     */
    private function loadType(AdapterInterface $adapter, AllowsTypeIndication $type)
    {
        $definition = $type->getTypeDefinition();

        if ($this->predefined[\get_class($definition)] ?? null) {
            return new $this->predefined[\get_class($definition)];
        }

        return $adapter->get($definition);
    }
}
