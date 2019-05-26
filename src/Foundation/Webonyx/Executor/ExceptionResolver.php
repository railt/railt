<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Executor;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Railt\Http\Exception\GraphQLException;
use Railt\Http\Exception\GraphQLProviderProviderInterface;
use Railt\Http\Exception\Location;

/**
 * Class ExceptionResolver
 */
class ExceptionResolver
{
    /**
     * @param \Throwable $exception
     * @return GraphQLProviderProviderInterface
     */
    public static function resolve(\Throwable $exception): GraphQLProviderProviderInterface
    {
        if ($exception instanceof Error) {
            return self::createFromWebonyx($exception);
        }

        return GraphQLException::fromThrowable($exception);
    }

    /**
     * @param Error $error
     * @return GraphQLProviderProviderInterface
     */
    private static function createFromWebonyx(Error $error): GraphQLProviderProviderInterface
    {
        $root = self::getRootException($error);

        $exception = $root instanceof GraphQLProviderProviderInterface
            ? $root
            : new GraphQLException(self::resolveMessage($error), $error->getCode(), $error);

        if ($error->isClientSafe() || $error->getCategory() === Error::CATEGORY_GRAPHQL) {
            $exception->publish();
        }

        foreach ($error->getLocations() as $location) {
            $exception->addLocation(new Location($location->line, $location->column));
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
            if ($error instanceof GraphQLProviderProviderInterface) {
                return $error;
            }

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
