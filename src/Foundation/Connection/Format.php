<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Connection;

use Railt\Dumper\TypeDumper;
use Railt\Http\BatchingResponse;
use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;

/**
 * Class Format
 */
class Format
{
    /**
     * @return array|RequestInterface[]
     */
    private static function empty(): array
    {
        return [new Request('')];
    }

    /**
     * @param iterable|RequestInterface|RequestInterface[] $requests
     * @return iterable|RequestInterface[]
     * @throws \InvalidArgumentException
     */
    public static function requests($requests): iterable
    {
        if ($requests instanceof RequestInterface) {
            $requests = [$requests];
        }

        if (\is_iterable($requests)) {
            return self::requestsFromIterable($requests);
        }

        return self::empty();
    }

    /**
     * @param iterable|RequestInterface[] $requests
     * @return array|RequestInterface[]
     * @throws \InvalidArgumentException
     */
    private static function requestsFromIterable(iterable $requests): array
    {
        $result = [];

        foreach ($requests as $item) {
            if (! $item instanceof RequestInterface) {
                throw self::invalidRequestException($item, 1);
            }

            $result[] = $item;
        }

        if (\count($result) === 0) {
            return self::empty();
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @param int $code
     * @return \InvalidArgumentException
     */
    private static function invalidRequestException($value, int $code = 0): \InvalidArgumentException
    {
        $error = 'Request object must be an instance of %s, but %s given';
        $error = \sprintf($error, RequestInterface::class, TypeDumper::render($value));

        return new \InvalidArgumentException($error, $code);
    }

    /**
     * @param array|ResponseInterface[] $responses
     * @return ResponseInterface
     */
    public static function responses(array $responses): ResponseInterface
    {
        switch (\count($responses)) {
            case 0:
                return Response::empty();

            case 1:
                return \reset($responses);

            default:
                return new BatchingResponse(...$responses);
        }
    }
}
