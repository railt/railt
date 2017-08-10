<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Http\Adapters;

use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Http\Support\ConfigurableRequest;
use Serafim\Railgun\Http\Support\ConfigurableRequestInterface;
use Serafim\Railgun\Http\Support\InteractWithData;
use Serafim\Railgun\Http\Support\JsonContentTypeHelper;

/**
 * Class NativeRequest
 * @package Serafim\Railgun\Http
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
        return (array)json_decode($this->getInputStream(), true);
    }

    /**
     * @return string
     */
    protected function getInputStream(): string
    {
        return @file_get_contents('php://input') ?: '';
    }

    /**
     * @return array
     */
    private function readRawRequest(): array
    {
        return array_merge($_GET, $_POST, $_REQUEST);
    }
}
