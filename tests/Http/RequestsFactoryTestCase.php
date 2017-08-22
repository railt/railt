<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Illuminate\Http\Request as LaravelNativeRequest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Exception;
use Railt\Http\Adapters\IlluminateRequest;
use Railt\Http\Adapters\NativeRequest;
use Railt\Http\Adapters\SymfonyRequest;
use Railt\Http\Request;
use Railt\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyNativeRequest;

/**
 * Class RequestsFactoryTestCase
 * @package Railt\Tests\Http
 */
class RequestsFactoryTestCase extends AbstractTestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws Exception
     */
    public function testNativeRequestResolved(): void
    {
        Assert::assertInstanceOf(NativeRequest::class, Request::create());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws Exception
     */
    public function testLaravelRequestResolved(): void
    {
        $laravel = Request::create(LaravelNativeRequest::createFromGlobals());

        Assert::assertInstanceOf(IlluminateRequest::class, $laravel);
    }

    /**
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    public function testSymfonyRequestResolved(): void
    {
        $symfony = Request::create(SymfonyNativeRequest::createFromGlobals());

        Assert::assertInstanceOf(SymfonyRequest::class, $symfony);
    }
}
