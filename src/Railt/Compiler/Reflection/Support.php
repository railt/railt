<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;

/**
 * Trait Support
 */
trait Support
{
    /**
     * @var string
     */
    private static $syntaxList = '[%s]';

    /**
     * @var string
     */
    private static $syntaxNonNull = '%s!';

    /**
     * @param AllowsTypeIndication|Definition $type
     * @return string
     */
    protected function typeToString(Definition $type): string
    {
        if ($type instanceof TypeDefinition) {
            [$parent, $name] = [$type->getTypeName(), $type->getName()];

            if ($type instanceof AllowsTypeIndication) {
                $name = \sprintf('%s: %s', $type->getName(), $this->typeIndicatorToString($type));
            }

            return \sprintf('%s %s', $parent, $name);
        }

        return $type->getName();
    }

    /**
     * @param AllowsTypeIndication $type
     * @return string
     */
    protected function typeIndicatorToString(AllowsTypeIndication $type): string
    {
        $result = $type->getTypeDefinition()->getName();

        if ($type->isList()) {
            if ($type->isListOfNonNulls()) {
                $result = \sprintf(self::$syntaxNonNull, $result);
            }

            $result = \sprintf(self::$syntaxList, $result);
        }

        if ($type->isNonNull()) {
            $result = \sprintf(self::$syntaxNonNull, $result);
        }

        return $result;
    }

    /**
     * @param $value
     * @return string
     */
    protected function valueWithType($value): string
    {
        return '(' . Str::lower(\gettype($value)) . ')' . $this->valueToString($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function valueToScalar($value)
    {
        if ($value === null) {
            return null;
        }

        if (\is_scalar($value)) {
            return (string)$value;
        }

        if (\is_iterable($value)) {
            $result = [];

            /** @var iterable $value */
            foreach ($value as $key => $sub) {
                $result[$key] = $this->valueToScalar($sub);
            }

            return $result;
        }

        if ($value instanceof Definition) {
            return $this->typeToString($value);
        }

        if ($value instanceof Arrayable) {
            return $this->valueToScalar($value->toArray());
        }

        if ($value instanceof \JsonSerializable) {
            return $this->valueToScalar(\json_encode($value->jsonSerialize(), true));
        }

        return Str::studly(\gettype($value));
    }

    /**
     * @param mixed|iterable|null $value
     * @return string
     */
    protected function valueToString($value): string
    {
        $result = $this->valueToScalar($value);

        return \json_encode($result);
    }
}
