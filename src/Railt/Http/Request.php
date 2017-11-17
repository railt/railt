<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Support\ConfigurableRequest;
use Railt\Http\Support\ConfigurableRequestInterface;
use Railt\Http\Support\InteractWithData;
use Railt\Http\Support\JsonContentTypeHelper;

/**
 * Class Request
 */
class Request implements RequestInterface, ConfigurableRequestInterface
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
        return (array)\json_decode($this->getInputStream(), true);
    }

    /**
     * @return string
     */
    protected function getInputStream(): string
    {
        return @\file_get_contents('php://input') ?: '';
    }

    /**
     * @return array
     */
    private function readRawRequest(): array
    {
        return \array_merge($_GET, $_POST, $_REQUEST);
    }
}
