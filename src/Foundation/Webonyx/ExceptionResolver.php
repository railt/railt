<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Railt\Http\Exception\GraphQLException;
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\Exception\GraphQLExceptionLocation;

/**
 * Class ExceptionResolver
 */
class ExceptionResolver
{
    /**
     * @param \Throwable $exception
     * @return GraphQLExceptionInterface
     */
    public static function resolve(\Throwable $exception): GraphQLExceptionInterface
    {
        if ($exception instanceof Error) {
            return self::createFromWebonyx($exception);
        }

        return GraphQLException::fromThrowable($exception);
    }

    /**
     * @param Error $error
     * @return GraphQLExceptionInterface
     */
    private static function createFromWebonyx(Error $error): GraphQLExceptionInterface
    {
        $root = self::getRootException($error);

        $exception = new GraphQLException(self::resolveMessage($error), $error->getCode(), $root);
        $exception->from($root);

        if ($error->isClientSafe() || $error->getCategory() === Error::CATEGORY_GRAPHQL) {
            $exception->publish();
        }

        foreach ($error->getLocations() as $location) {
            $exception->addLocation(new GraphQLExceptionLocation($location->line, $location->column));
        }

        foreach ((array)$error->getPath() as $chunk) {
            $exception->addPath($chunk);
        }

        return $exception;
    }

    /**
     * @param \Throwable $error
     * @return \Throwable
     */
    private static function getRootException(\Throwable $error): \Throwable
    {
        while ($error->getPrevious()) {
            $error = $error->getPrevious();
        }

        return $error;
    }

    /**
     * @param Error $error
     * @return string
     */
    private static function resolveMessage(Error $error): string
    {
        if ($error instanceof InvariantViolation) {
            return 'GraphQL SDL Exception: ' . $error->getMessage();
        }

        return $error->getMessage();
    }
}
