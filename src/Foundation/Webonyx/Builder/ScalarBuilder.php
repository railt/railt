<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use Carbon\Carbon;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Railt\Component\Dumper\TypeDumper;
use Railt\Component\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\Foundation\Webonyx\Exception\ParsingException;
use Railt\Foundation\Webonyx\Exception\SerializationException;

/**
 * Class ScalarBuilder
 *
 * @property ScalarDefinition $reflection
 */
class ScalarBuilder extends Builder
{
    /**
     * @return ScalarType
     */
    public function build(): ScalarType
    {
        return $this->builtin($this->reflection, function (ScalarDefinition $reflection) {
            return $this->create($reflection);
        });
    }

    /**
     * @param ScalarDefinition $reflection
     * @param \Closure $otherwise
     * @return ScalarType
     */
    private function builtin(ScalarDefinition $reflection, \Closure $otherwise): ScalarType
    {
        switch ($reflection->getName()) {
            case 'String':
                return Type::string();

            case 'ID':
                return Type::id();

            case 'Boolean':
                return Type::boolean();

            case 'Int':
                return Type::int();

            case 'Float':
                return Type::float();

            case 'DateTime':
                return $this->createDateTime($reflection);
        }

        return $otherwise($reflection);
    }

    /**
     * @param ScalarDefinition $reflection
     * @return ScalarType
     * @throws InvariantViolation
     */
    private function createDateTime(ScalarDefinition $reflection): ScalarType
    {
        $serializeDateTime = function ($value): string {
            switch (true) {
                case \is_string($value):
                    return Carbon::parse($value)->toRfc3339String();

                case \is_int($value):
                    return Carbon::createFromTimestamp($value)->toRfc3339String();

                case $value instanceof \DateTimeInterface:
                    return Carbon::instance($value)->toRfc3339String();
            }

            $error = 'Can not serialize type %s to valida DateTime string';

            $exception = new SerializationException(\sprintf($error, TypeDumper::render($value)));
            $exception->publish();

            throw $exception;
        };

        $parseDateTime = function ($value): \DateTimeInterface {
            switch (true) {
                case \is_string($value):
                    return Carbon::parse($value);

                case \is_int($value):
                    return Carbon::createFromTimestamp($value);

                case $value instanceof \DateTimeInterface:
                    return Carbon::instance($value);
            }

            $error = '%s is not a valid DateTime type';

            $exception = new ParsingException(\sprintf($error, TypeDumper::render($value)));
            $exception->publish();

            throw $exception;
        };

        return new CustomScalarType([
            'name'         => $reflection->getName(),
            'description'  => $reflection->getDescription(),
            'serialize'    => function ($value) use ($serializeDateTime): string {
                return $serializeDateTime($value);
            },
            'parseValue'   => function ($value) use ($parseDateTime): \DateTimeInterface {
                return $parseDateTime($value);
            },
            'parseLiteral' => function ($value) use ($parseDateTime): \DateTimeInterface {
                return $parseDateTime($value);
            },
        ]);
    }

    /**
     * @param ScalarDefinition $reflection
     * @return ScalarType
     * @throws InvariantViolation
     */
    private function create(ScalarDefinition $reflection): ScalarType
    {
        return new CustomScalarType([
            'name'         => $reflection->getName(),
            'description'  => $reflection->getDescription(),
            'serialize'    => function ($value) {
                return $this->serialize($value);
            },
            'parseValue'   => function ($value) {
                return $this->parse($value);
            },
            'parseLiteral' => function ($value) {
                return $this->parse($value);
            },
        ]);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function serialize($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function parse($value)
    {
        if (\is_object($value) && \property_exists($value, 'value')) {
            return $value->value;
        }

        return $value;
    }
}
