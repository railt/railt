<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Factory;

use Railt\Http\Provider\DataProvider;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\Request;

/**
 * Class GlobalsPrioritiesTestCase
 */
class PostGlobalsPriorityTestCase extends FactoryTestCase
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
        return DataProvider::new([
            'query'         => $this->string(3),
            'variables'     => ['a' => $this->string(4)],
            'operationName' => $this->string(5),
        ], [
            'query'         => $this->string(6),
            'variables'     => ['a' => $this->string(7)],
            'operationName' => $this->string(8),
        ])
            ->withContentType('application/json')
            ->withBody(\json_encode([
                'query'         => $this->string(0),
                'variables'     => ['a' => $this->string(1)],
                'operationName' => $this->string(2),
            ]));
    }
}
