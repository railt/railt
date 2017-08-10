<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Http;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Serafim\Railgun\Http\Adapters\IlluminateRequest;
use Serafim\Railgun\Http\Adapters\NativeRequest;
use Serafim\Railgun\Http\Adapters\SymfonyRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

/**
 * Class Request
 * @package Serafim\Railgun\Http
 */
class Request
{
    /**
     * @var array
     */
    protected static $adapters = [
        IlluminateHttpRequest::class => IlluminateRequest::class,
        SymfonyHttpRequest::class    => SymfonyRequest::class,
    ];

    /**
     * @var string
     */
    protected static $default = NativeRequest::class;

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

        return new static::$default();
    }

}
