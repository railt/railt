<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Requests;

use Serafim\Railgun\Requests\Support\InteractWithData;
use Serafim\Railgun\Requests\Support\ConfigurableRequest;
use Serafim\Railgun\Requests\Support\JsonContentTypeHelper;
use Serafim\Railgun\Requests\Support\ConfigurableRequestInterface;

/**
 * Class NativeRequest
 * @package Serafim\Railgun\Requests
 */
class NativeRequest implements RequestInterface, ConfigurableRequestInterface
{
    use InteractWithData;
    use ConfigurableRequest;
    use JsonContentTypeHelper;

    /**
     * NativeRequest constructor.
     */
    public function __construct()
    {
        $this->data = $this->isJson($_SERVER['CONTENT_TYPE'] ?? '')
            ? $this->readJsonRequest()
            : $this->readRawRequest();
    }

    /**
     * @return array
     */
    private function readJsonRequest(): array
    {
        $input = @file_get_contents('php://input') ?: '';

        return (array)json_decode($input, true);
    }

    /**
     * @return array
     */
    private function readRawRequest(): array
    {
        return array_merge($_POST, $_REQUEST, $_GET);
    }
}
