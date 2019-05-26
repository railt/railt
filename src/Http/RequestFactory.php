<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Railt\Http\Resolver\JsonBodyResolver;
use Railt\Http\Resolver\BodyParamsResolver;
use Railt\Http\Resolver\QueryParamsResolver;
use Railt\Http\Resolver\MutableResolverProviderTrait;
use Railt\Http\Resolver\MutableResolversProviderInterface;

/**
 * Class RequestFactory
 */
class RequestFactory implements RequestFactoryInterface, MutableResolversProviderInterface
{
    use MutableResolverProviderTrait;

    /**
     * @return RequestFactory|static
     */
    public static function create(): self
    {
        return (new static())
            ->withResolver(
                new JsonBodyResolver(),
                new BodyParamsResolver(),
                new QueryParamsResolver()
            );
    }

    /**
     * @param ServerRequestInterface $request
     * @return RequestInterface
     */
    public function fromServerRequest(ServerRequestInterface $request): RequestInterface
    {
        foreach ($this->getResolvers() as $resolver) {
            if ($graphql = $resolver->resolve($request)) {
                return $graphql;
            }
        }

        return $this->empty();
    }

    /**
     * @return RequestInterface
     */
    public function empty(): RequestInterface
    {
        return new Request('');
    }
}
