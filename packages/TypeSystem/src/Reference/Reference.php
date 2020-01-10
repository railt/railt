<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Reference;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Exception\IncompatibleTypeException;

/**
 * Class Reference
 */
class Reference
{
    /**
     * @var string
     */
    private const ERROR_INCORRECT_REFERENCE = 'Incorrect reference. An %s is required, but %s is returned';

    /**
     * @param DefinitionInterface $ctx
     * @param TypeReferenceInterface|null $ref
     * @param string $type
     * @return NamedTypeInterface|null
     */
    public static function resolveNullable(
        DefinitionInterface $ctx,
        ?TypeReferenceInterface $ref,
        string $type
    ): ?NamedTypeInterface {
        if ($ref === null) {
            return null;
        }

        return static::resolve($ctx, $ref, $type);
    }

    /**
     * @param DefinitionInterface $ctx
     * @param TypeReferenceInterface $ref
     * @param string $type
     * @return NamedTypeInterface
     */
    public static function resolve(
        DefinitionInterface $ctx,
        TypeReferenceInterface $ref,
        string $type
    ): NamedTypeInterface {
        $result = $ref->getType($ctx);

        if ($result instanceof $type) {
            return $result;
        }

        $message = \sprintf(self::ERROR_INCORRECT_REFERENCE, $type, \get_class($result));

        throw new IncompatibleTypeException($message);
    }
}
