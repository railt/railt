<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Mocks;

use Railt\Http\Request;

/**
 * Class NativeRequestMock
 */
class NativeRequest extends Request
{
    /**
     * @var string
     */
    private $body;

    /**
     * NativeRequestMock constructor.
     * @param string $body
     * @param bool $emulateJson
     */
    public function __construct(string $body, bool $emulateJson = true)
    {
        $this->wrapGlobals($emulateJson, function () use ($body): void {
            $this->body = $body;
            parent::__construct();
        });
    }

    /**
     * @param bool $emulateJson
     * @param \Closure $then
     * @return void
     */
    private function wrapGlobals(bool $emulateJson, \Closure $then): void
    {
        if ($emulateJson) {
            $contentType             = $_SERVER['CONTENT_TYPE'] ?? 'text/html';
            $_SERVER['CONTENT_TYPE'] = 'application/json';
            $then();
            $_SERVER['CONTENT_TYPE'] = $contentType;
        } else {
            $then();
        }
    }

    /**
     * @return string
     */
    final protected function getInputStream(): string
    {
        return $this->body;
    }
}
