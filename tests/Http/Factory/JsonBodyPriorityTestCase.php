<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Factory;

use Railt\Component\Http\Provider\GlobalsProvider;
use Railt\Component\Http\Provider\ProviderInterface;
use Railt\Component\Http\Request;

/**
 * Class JsonBodyPriorityTestCase
 */
class JsonBodyPriorityTestCase extends FactoryTestCase
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
        $_REQUEST = $_SERVER = $_SESSION = [];

        $_POST = [
            'query'         => $this->string(0),
            'variables'     => ['a' => $this->string(1)],
            'operationName' => $this->string(2),
        ];

        $_GET = [
            'query'         => $this->string(3),
            'variables'     => ['a' => $this->string(4)],
            'operationName' => $this->string(5),
        ];

        return new GlobalsProvider();
    }
}
