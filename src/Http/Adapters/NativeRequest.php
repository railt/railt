<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Http\Adapters;

use Railgun\Http\RequestInterface;
use Railgun\Http\Support\ConfigurableRequest;
use Railgun\Http\Support\ConfigurableRequestInterface;
use Railgun\Http\Support\InteractWithData;
use Railgun\Http\Support\JsonContentTypeHelper;

/**
 * Class NativeRequest
 * @package Railgun\Http
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
