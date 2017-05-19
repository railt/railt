<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Requests;

use Illuminate\Http\Request;
use Serafim\Railgun\Requests\Support\InteractWithData;
use Serafim\Railgun\Requests\Support\ConfigurableRequest;
use Serafim\Railgun\Requests\Support\ConfigurableRequestInterface;

/**
 * Class IlluminateRequest
 * @package Serafim\Railgun\Requests
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
            : $request->all();
    }
}
