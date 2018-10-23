<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Zend\Http\Header\ContentType;
use Zend\Http\Request;

/**
 * Class ZendHttpProvider
 */
class ZendHttpProvider extends Provider
{
    /**
     * @var Request
     */
    private $request;

    /**
     * ZendHttpProvider constructor.
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
        return $this->matchJson($this->getContentType());
    }

    /**
     * @return string
     */
    private function getContentType(): string
    {
        try {
            $type = $this->request->getHeader(static::CONTENT_TYPE_KEY);

            if ($type instanceof ContentType) {
                return $type->getFieldValue();
            }

            return static::CONTENT_TYPE_DEFAULT;
        } catch (\Throwable $e) {
            return static::CONTENT_TYPE_DEFAULT;
        }
    }

    /**
     * @return array
     */
    protected function getJson(): array
    {
        try {
            $content = (string)$this->request->getContent();

            return $this->parseJson($content);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * @return iterable
     */
    protected function getRequestArguments(): iterable
    {
        return \array_merge(
            $this->request->getQuery()->toArray(),
            $this->request->getPost()->toArray()
        );
    }
}
