<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL;

use Railt\Component\Json\Json;
use Railt\Component\SDL\Contracts\Behavior\AllowsTypeIndication;
use Railt\Component\SDL\Contracts\Definitions\Definition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\Component\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Trait Support
 */
trait Support
{
    /**
     * @var string
     */
    private static $typeDefinition = '%s<%s>';

    /**
     * @var string
     */
    private static $fieldDefinition = '{%s: %s}';

    /**
     * @var string
     */
    private static $argumentDefinition = '(%s: %s)';

    /**
     * @var string
     */
    private static $syntaxList = '[%s]';

    /**
     * @var string
     */
    private static $syntaxNonNull = '%s!';

    /**
     * @param AllowsTypeIndication&Definition $type
     * @return string
     */
    protected function typeToString(Definition $type): string
    {
        if ($type instanceof ArgumentDefinition) {
            return \vsprintf(self::$argumentDefinition, [
                $type->getName(),
                $this->typeIndicatorToString($type),
            ]);
        }

        if ($type instanceof FieldDefinition) {
            return \vsprintf(self::$fieldDefinition, [
                $type->getName(),
                $this->typeIndicatorToString($type),
            ]);
        }

        if ($type instanceof TypeDefinition) {
            return \sprintf(self::$typeDefinition, $type->getTypeName(), $type->getName());
        }

        return $type->getName();
    }

    /**
     * @param AllowsTypeIndication $type
     * @return string
     */
    protected function typeIndicatorToString(AllowsTypeIndication $type): string
    {
        try {
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
        } catch (\Throwable $e) {
            return '?';
        }
    }

    /**
     * @param mixed $value
     * @return string
     * @throws \Railt\Component\Json\Exception\JsonException
     */
    protected function valueWithType($value): string
    {
        return \mb_strtolower(\gettype($value)) . ' ' . $this->valueToString($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \Railt\Component\Json\Exception\JsonException
     */
    protected function valueToScalar($value)
    {
        if (\is_scalar($value)) {
            return $value;
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

        if ($value instanceof \Illuminate\Contracts\Support\Arrayable) {
            return $this->valueToScalar($value->toArray());
        }

        if ($value instanceof \Illuminate\Contracts\Support\Jsonable) {
            $decoder = Json::decoder()->withOptions(\JSON_OBJECT_AS_ARRAY);

            return $this->valueToScalar($decoder->decode($value->toJson()));
        }

        if ($value instanceof \JsonSerializable) {
            return $this->valueToScalar(Json::encode($value->jsonSerialize()));
        }

        return \ucfirst(\strtolower(\gettype($value)));
    }

    /**
     * @param mixed|iterable|null $value
     * @return string
     * @throws \Railt\Component\Json\Exception\JsonException
     */
    protected function valueToString($value): string
    {
        $result = $this->valueToScalar($value);

        if (\is_array($result)) {
            $result = Json::encode($result);
            $result = \preg_replace('/"([_A-Za-z][_0-9A-Za-z]*)":/u', '$1: ', $result);
            $result = \preg_replace('/:\s+(.*?),/u', ': $1, ', $result);

            return $result;
        }

        return (string)$result;
    }
}
