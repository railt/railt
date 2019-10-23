<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Http\Request;
use Railt\Contracts\Http\RequestInterface;
use Railt\HttpFactory\Provider\DataProvider;
use Railt\HttpFactory\Resolver\JsonBodyResolver;
use Railt\Contracts\HttpFactory\FactoryInterface;
use Railt\HttpFactory\Provider\PsrMessageProvider;
use Psr\Http\Message\RequestInterface as PsrRequest;
use Psr\Http\Message\MessageInterface as PsrMessage;
use Railt\HttpFactory\Resolver\PostArgumentsResolver;
use Railt\HttpFactory\Resolver\QueryArgumentsResolver;
use Railt\HttpFactory\Provider\PsrServerRequestProvider;
use Railt\Contracts\HttpFactory\Provider\ProviderInterface;
use Railt\Contracts\HttpFactory\Resolver\ResolverInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequest;

/**
 * Class Factory
 */
class Factory implements FactoryInterface
{
    use HeadersTrait;
    
    /**
     * @var array|ResolverInterface[]
     */
    private array $resolvers;

    /**
     * Factory constructor.
     */
    public function __construct()
    {
        $this->resolvers = [
            new JsonBodyResolver(),
            new PostArgumentsResolver(),
            new QueryArgumentsResolver(),
        ];
    }

    /**
     * @param ResolverInterface $resolver
     * @return FactoryInterface|$this
     */
    public function add(ResolverInterface $resolver): FactoryInterface
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function fromGlobals(): RequestInterface
    {
        $headers = $this->getGlobalHeaders();
        
        $reader = fn () => (string)\file_get_contents('php://input');

        $provider = new DataProvider($_GET ?? [], $_POST ?? [], $headers, $reader);

        return $this->create($provider);
    }

    /**
     * @param ProviderInterface $provider
     * @return Request
     */
    public function create(ProviderInterface $provider): RequestInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($request = $resolver->resolve($provider)) {
                return $request;
            }
        }

        return new Request('');
    }

    /**
     * @return RequestInterface
     */
    public function empty(): RequestInterface
    {
        return new Request('');
    }

    /**
     * @param PsrServerRequest $request
     * @return RequestInterface
     */
    public function fromServerRequest(PsrServerRequest $request): RequestInterface
    {
        return $this->create(new PsrServerRequestProvider($request));
    }

    /**
     * @param PsrRequest $request
     * @return RequestInterface
     */
    public function fromRequest(PsrRequest $request): RequestInterface
    {
        return $this->fromMessage($request);
    }

    /**
     * @param PsrMessage $message
     * @return RequestInterface
     */
    public function fromMessage(PsrMessage $message): RequestInterface
    {
        return $this->create(new PsrMessageProvider($message));
    }
}
