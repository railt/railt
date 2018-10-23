<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Illuminate\Http\Request;

/**
 * Class IlluminateProvider
 */
class IlluminateProvider extends Provider
{
    /**
     * @var Request
     */
    private $request;

    /**
     * IlluminateProvider constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    protected function isJson(): bool
    {
        return $this->request->isJson();
    }

    /**
     * @return array
     * @throws \LogicException
     */
    protected function getJson(): array
    {
        return $this->request->json()->all();
    }

    /**
     * @return iterable
     */
    protected function getRequestArguments(): iterable
    {
        return \array_merge(
            $this->request->query->all(),
            $this->request->request->all()
        );
    }
}
