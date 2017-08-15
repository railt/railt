<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Http;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Railgun\Http\Adapters\IlluminateRequest;
use Railgun\Http\Adapters\NativeRequest;
use Railgun\Http\Adapters\SymfonyRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

/**
 * Class Request
 * @package Railgun\Http
 */
class Request extends NativeRequest
{
    /**
     * @var array
     */
    protected static $adapters = [
        IlluminateHttpRequest::class => IlluminateRequest::class,
        SymfonyHttpRequest::class    => SymfonyRequest::class,
    ];

    /**
     * @param null|mixed $request
     * @return RequestInterface
     */
    public static function createFrom($request = null): RequestInterface
    {
        if ($request !== null) {
            foreach (static::$adapters as $original => $adapter) {
                if ($request instanceof $original) {
                    return new $adapter($request);
                }
            }
        }

        return new static();
    }
}
