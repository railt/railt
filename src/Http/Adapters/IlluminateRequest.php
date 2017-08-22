<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Adapters;

use Illuminate\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Http\Support\ConfigurableRequest;
use Railt\Http\Support\ConfigurableRequestInterface;
use Railt\Http\Support\InteractWithData;

/**
 * Class IlluminateRequest
 * @package Railt\Http
 */
class IlluminateRequest implements RequestInterface, ConfigurableRequestInterface
{
    use InteractWithData;
    use ConfigurableRequest;

    /**
     * IlluminateRequest constructor.
     * @param Request $request
     * @throws \LogicException
     */
    public function __construct(Request $request)
    {
        $this->data = $request->isJson()
            ? $request->json()->all()
            : array_merge($request->all(), $request->request->all());
    }
}
