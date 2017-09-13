<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Railt\Http\Request;
use PHPUnit\Framework\Assert;
use Railt\Http\RequestInterface;

/**
 * Class NativeRequestTestCase
 * @package Railt\Tests\Http
 */
class NativeRequestTestCase extends AbstractHttpRequestTestCase
{
    /**
     * @param string $body
     * @param bool $makeJson
     * @return RequestInterface
     */
    protected function request(string $body, bool $makeJson = true): RequestInterface
    {
        if ($makeJson) {
            $_SERVER['CONTENT_TYPE'] = 'application/json';
        }

        return new class($body) extends Request {
            /**
             * @var string
             */
            private $body;

            /**
             * Anonymous class constructor.
             * @param string $body
             */
            public function __construct(string $body)
            {
                $this->body = $body;
                parent::__construct();
            }

            /**
             * @return string
             */
            final protected function getInputStream(): string
            {
                Assert::assertEquals('', parent::getInputStream());

                return $this->body;
            }
        };
    }
}
