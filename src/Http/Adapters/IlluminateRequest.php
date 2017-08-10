<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Http\Adapters;

use Illuminate\Http\Request;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Http\Support\ConfigurableRequest;
use Serafim\Railgun\Http\Support\ConfigurableRequestInterface;
use Serafim\Railgun\Http\Support\InteractWithData;

/**
 * Class IlluminateRequest
 * @package Serafim\Railgun\Http
 */
class IlluminateRequest implements RequestInterface, ConfigurableRequestInterface
{
    use InteractWithData;
    use ConfigurableRequest;

    /**
     * IlluminateRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->data = $request->isJson()
            ? $request->json()->all()
            : array_merge($request->all(), $request->request->all());
    }
}
