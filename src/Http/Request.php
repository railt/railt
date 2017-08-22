<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Railt\Http\Adapters\IlluminateRequest;
use Railt\Http\Adapters\NativeRequest;
use Railt\Http\Adapters\SymfonyRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

/**
 * Class Request
 * @package Railt\Http
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
    public static function create($request = null): RequestInterface
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
