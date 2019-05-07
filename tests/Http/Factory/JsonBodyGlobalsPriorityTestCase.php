<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Factory;

use Railt\Http\Provider\GlobalsProvider;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\Request;

/**
 * Class JsonBodyGlobalsPriorityTestCase
 */
class JsonBodyGlobalsPriorityTestCase extends FactoryTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    protected function getRequests(): array
    {
        return [
            Request::create($this->string(0), ['a' => $this->string(1)], $this->string(2)),
        ];
    }

    /**
     * @return ProviderInterface
     * @throws \Exception
     */
    protected function getProvider(): ProviderInterface
    {
        $_SERVER['CONTENT_TYPE'] = 'application/json';

        $_POST = [
            'query'         => $this->string(6),
            'variables'     => ['a' => $this->string(7)],
            'operationName' => $this->string(8),
        ];

        $_GET = [
            'query'         => $this->string(3),
            'variables'     => ['a' => $this->string(4)],
            'operationName' => $this->string(5),
        ];


        $body = \vsprintf('{"query": "%s", "variables": {"a": "%s"}, "operationName": "%s"}', [
            $this->string(0),
            $this->string(1),
            $this->string(2),
        ]);

        $globals = new class($body) extends GlobalsProvider {
            /**
             * @var string
             */
            private $body;

            public function __construct(string $body)
            {
                $this->body = $body;
            }

            public function getBody(): string
            {
                return $this->body;
            }
        };

        return $globals;
    }
}
