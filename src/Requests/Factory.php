<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Requests;

use Illuminate\Http\Request as IlluminateHttpRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

/**
 * Class Factory
 * @package Serafim\Railgun\Requests
 */
class Factory
{
    /**
     * @var array
     */
    private static $adapters = [
        IlluminateHttpRequest::class => IlluminateRequest::class,
        SymfonyHttpRequest::class    => SymfonyRequest::class,
    ];

    /**
     * @param null $request
     * @return NativeRequest
     */
    public static function create($request = null)
    {
        if ($request === null) {
            return new NativeRequest();
        }

        foreach (self::$adapters as $original => $adapter) {
            if ($request instanceof $original) {
                return new $adapter($request);
            }
        }

        return new NativeRequest();
    }
}
